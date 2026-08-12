<?php $__env->startSection('title', 'Data Pesanan'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-stone-900">Data Pesanan</h1>
        <p class="text-stone-500 text-sm mt-1">Kelola seluruh pesanan customer Fix Advertising.</p>
    </div>
    <?php if(auth()->user()->isMarketing()): ?>
    <a href="<?php echo e(route('pesanan.create')); ?>" class="inline-flex items-center justify-center gap-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition shadow-sm shadow-brand-500/20 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
        Buat Pesanan
    </a>
    <?php endif; ?>
</div>


<form method="GET" class="bg-white border border-stone-200 rounded-2xl p-4 mb-6 flex flex-col lg:flex-row gap-3">
    <div class="flex-1 relative">
        <svg class="w-4 h-4 text-stone-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M19 11a8 8 0 11-16 0 8 8 0 0116 0z" /></svg>
        <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Cari nomor invoice atau nama customer..."
            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-stone-300 text-sm placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
    </div>
    
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
        <?php if(auth()->user()->isMarketing()): ?>
        <a href="<?php echo e(route('pesanan.pdf', request()->query())); ?>" target="_blank" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-medium transition shrink-0 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
            PDF
        </a>
        <?php endif; ?>
    </div>
</form>


<div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-stone-400 uppercase tracking-wide border-b border-stone-100">
                    <th class="px-5 py-3 font-medium">No</th>
                    <th class="px-5 py-3 font-medium">Invoice</th>
                    <th class="px-5 py-3 font-medium">Tgl Pesanan</th>
                    <th class="px-5 py-3 font-medium">Customer</th>
                    <th class="px-5 py-3 font-medium">Produk</th>
                    <th class="px-5 py-3 font-medium">Jumlah</th>
                    <th class="px-5 py-3 font-medium">Deadline</th>
                    <th class="px-5 py-3 font-medium">Teknisi</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                <?php $__empty_1 = true; $__currentLoopData = $pesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-stone-50 cursor-pointer" onclick="window.location='<?php echo e(route('pesanan.show', $item)); ?>'">
                    <td class="px-5 py-3.5 text-stone-500"><?php echo e($loop->iteration); ?></td>
                    <td class="px-5 py-3.5 font-medium text-stone-900"><?php echo e($item->nomor_invoice); ?></td>
                    <td class="px-5 py-3.5 text-stone-600"><?php echo e($item->created_at->format('d M Y')); ?></td>
                    <td class="px-5 py-3.5 text-stone-600"><?php echo e($item->nama_customer); ?></td>
                    <td class="px-5 py-3.5 text-stone-600"><?php echo e($item->produk->nama_produk); ?></td>
                    <td class="px-5 py-3.5 text-stone-600"><?php echo e($item->jumlah); ?></td>
                    <td class="px-5 py-3.5 text-stone-600"><?php echo e($item->deadline->format('d M Y')); ?></td>
                    <td class="px-5 py-3.5 text-stone-600"><?php echo e($item->kode_teknisi ?? '—'); ?></td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset <?php echo e($item->statusBadgeClass()); ?>"><?php echo e($item->statusLabel()); ?></span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="9" class="px-5 py-10 text-center text-stone-400">Tidak ada pesanan ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($pesanan->hasPages()): ?>
    <div class="px-5 py-4 border-t border-stone-100">
        <?php echo e($pesanan->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\NIzar Hasdian\Documents\fixads-laravel\fixads\resources\views/pesanan/index.blade.php ENDPATH**/ ?>