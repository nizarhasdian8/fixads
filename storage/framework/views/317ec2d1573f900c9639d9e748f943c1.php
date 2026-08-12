<?php $__env->startSection('title', auth()->user()->isMarketing() ? 'Persetujuan Bahan' : 'Permintaan Bahan'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-stone-900"><?php echo e(auth()->user()->isMarketing() ? 'Persetujuan Permintaan Bahan' : 'Permintaan Bahan Baku'); ?></h1>
        <p class="text-stone-500 text-sm mt-1">
            <?php if(auth()->user()->isMarketing()): ?>
                Setujui atau tolak permintaan pembelian bahan baku dari CIO Production.
            <?php else: ?>
                Ajukan permintaan pembelian bahan baku ketika stok kosong atau habis.
            <?php endif; ?>
        </p>
    </div>
    <?php if(auth()->user()->isProduction()): ?>
    <a href="<?php echo e(route('pengajuan.create')); ?>" class="inline-flex items-center justify-center gap-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition shadow-sm shadow-brand-500/20 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
        Ajukan Permintaan
    </a>
    <?php endif; ?>
</div>


<form method="GET" class="bg-white border border-stone-200 rounded-2xl p-4 mb-6 flex flex-col lg:flex-row gap-3">
    <div class="flex gap-3">
        
        <select name="bulan" class="px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
            <option value="">Semua Bulan</option>
            <?php $__currentLoopData = $namaBulan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($num); ?>" <?php if((string)$bulanFilter === (string)$num): echo 'selected'; endif; ?>><?php echo e($name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <select name="tahun" class="px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
            <option value="">Semua Tahun</option>
            <?php $__currentLoopData = $tahunOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $th): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($th); ?>" <?php if((string)$tahunFilter === (string)$th): echo 'selected'; endif; ?>><?php echo e($th); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <select name="status" class="px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
            <option value="">Semua Status</option>
            <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($value); ?>" <?php if($statusFilter === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="flex gap-2">
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-stone-800 text-white text-sm font-medium hover:bg-stone-700 transition shrink-0">Filter</button>
        <a href="<?php echo e(route('pengajuan.pdf', ['bulan' => $bulanFilter, 'tahun' => $tahunFilter, 'status' => $statusFilter])); ?>" target="_blank" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-medium transition shrink-0 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
            PDF
        </a>
    </div>
</form>

<div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-stone-400 uppercase tracking-wide border-b border-stone-100">
                    <th class="px-5 py-3 font-medium">No</th>
                    <th class="px-5 py-3 font-medium">No. Permintaan</th>
                    <th class="px-5 py-3 font-medium">Tgl Pengajuan</th>
                    <th class="px-5 py-3 font-medium">Bahan Baku</th>
                    <th class="px-5 py-3 font-medium">Jumlah</th>
                    <th class="px-5 py-3 font-medium">Diajukan Oleh</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                <?php $__empty_1 = true; $__currentLoopData = $permintaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-stone-50 cursor-pointer" onclick="window.location='<?php echo e(route('pengajuan.show', $item)); ?>'">
                    <td class="px-5 py-3.5 text-stone-500"><?php echo e($loop->iteration); ?></td>
                    <td class="px-5 py-3.5 font-medium text-stone-900"><?php echo e($item->nomor_permintaan); ?></td>
                    <td class="px-5 py-3.5 text-stone-600"><?php echo e($item->created_at->format('d M Y')); ?></td>
                    <td class="px-5 py-3.5 text-stone-700"><?php echo e($item->bahanBaku->nama); ?></td>
                    <td class="px-5 py-3.5 text-stone-600"><?php echo e(rtrim(rtrim($item->jumlah, '0'), '.')); ?> <?php echo e($item->bahanBaku->satuan); ?></td>
                    <td class="px-5 py-3.5 text-stone-500"><?php echo e($item->pengaju->name); ?></td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset <?php echo e($item->statusBadgeClass()); ?>"><?php echo e($item->statusLabel()); ?></span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="px-5 py-10 text-center text-stone-400">Belum ada permintaan bahan baku pada bulan ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($permintaan->hasPages()): ?>
    <div class="px-5 py-4 border-t border-stone-100"><?php echo e($permintaan->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\NIzar Hasdian\Documents\fixads-laravel\fixads\resources\views/pengajuan/index.blade.php ENDPATH**/ ?>