<?php $__env->startSection('title', 'Bahan Masuk'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-stone-900">Bahan Masuk</h1>
        <p class="text-stone-500 text-sm mt-1">Riwayat pencatatan bahan baku yang datang dari supplier.</p>
    </div>
    <a href="<?php echo e(route('bahan-masuk.create')); ?>" class="inline-flex items-center justify-center gap-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition shadow-sm shadow-brand-500/20 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
        Catat Bahan Masuk
    </a>
</div>


<form method="GET" class="bg-white border border-stone-200 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row gap-3">
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
    </div>

    <button type="submit" class="px-5 py-2.5 rounded-xl bg-stone-800 text-white text-sm font-medium hover:bg-stone-700 transition shrink-0">Filter</button>
</form>

<div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-stone-400 uppercase tracking-wide border-b border-stone-100">
                    <th class="px-5 py-3 font-medium">No. Transaksi</th>
                    <th class="px-5 py-3 font-medium">Tanggal</th>
                    <th class="px-5 py-3 font-medium">Bahan Baku</th>
                    <th class="px-5 py-3 font-medium">Jumlah</th>
                    <th class="px-5 py-3 font-medium">Permintaan Terkait</th>
                    <th class="px-5 py-3 font-medium">Dicatat Oleh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                <?php $__empty_1 = true; $__currentLoopData = $bahanMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-stone-50">
                    <td class="px-5 py-3.5 font-medium text-stone-900"><?php echo e($item->nomor_transaksi); ?></td>
                    <td class="px-5 py-3.5 text-stone-600"><?php echo e($item->tanggal->format('d M Y')); ?></td>
                    <td class="px-5 py-3.5 text-stone-700"><?php echo e($item->bahanBaku->nama); ?></td>
                    <td class="px-5 py-3.5 text-stone-600">+<?php echo e(rtrim(rtrim($item->jumlah, '0'), '.')); ?> <?php echo e($item->bahanBaku->satuan); ?></td>
                    <td class="px-5 py-3.5 text-stone-500">
                        <?php if($item->permintaanBahan): ?>
                            <a href="<?php echo e(route('pengajuan.show', $item->permintaanBahan)); ?>" class="text-brand-600 hover:underline"><?php echo e($item->permintaanBahan->nomor_permintaan); ?></a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3.5 text-stone-500"><?php echo e($item->pencatat->name); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-5 py-10 text-center text-stone-400">Belum ada riwayat bahan masuk pada bulan ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($bahanMasuk->hasPages()): ?>
    <div class="px-5 py-4 border-t border-stone-100"><?php echo e($bahanMasuk->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\NIzar Hasdian\Documents\fixads-laravel\fixads\resources\views/bahan-masuk/index.blade.php ENDPATH**/ ?>