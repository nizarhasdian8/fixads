<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-stone-900">Dashboard</h1>
    <p class="text-stone-500 text-sm mt-1">Ringkasan stok bahan baku dan antrian produksi.</p>
</div>


<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    
    <a href="<?php echo e(route('bahan-baku.index')); ?>" class="bg-white border border-stone-200 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Bahan Menipis</p>
            <p class="text-3xl font-bold text-stone-900 mt-1"><?php echo e($bahanMenipis); ?></p>
            <p class="text-xs text-stone-500">Item perlu diajukan</p>
        </div>
    </a>

    
    <a href="<?php echo e(route('laporan.harian')); ?>" class="bg-white border border-stone-200 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16M14 6l6 6-6 6" /></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Bahan Masuk</p>
            <p class="text-3xl font-bold text-stone-900 mt-1"><?php echo e($bahanMasukHariIni); ?></p>
            <p class="text-xs text-stone-500">Transaksi hari ini</p>
        </div>
    </a>

    
    <a href="<?php echo e(route('laporan.harian')); ?>" class="bg-white border border-stone-200 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4M10 18l-6-6 6-6" /></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Bahan Keluar</p>
            <p class="text-3xl font-bold text-stone-900 mt-1"><?php echo e($bahanKeluarHariIni); ?></p>
            <p class="text-xs text-stone-500">Pemakaian hari ini</p>
        </div>
    </a>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    
    <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-stone-200">
            <h2 class="font-semibold text-stone-900">Stok Hampir Habis</h2>
            <a href="<?php echo e(route('bahan-baku.index')); ?>" class="text-sm font-medium text-brand-600 hover:text-brand-700">Lihat semua &rarr;</a>
        </div>
        <div class="divide-y divide-stone-100">
            <?php $__empty_1 = true; $__currentLoopData = $bahanHampirHabis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bahan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="px-5 py-3.5 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-stone-900"><?php echo e($bahan->nama); ?></p>
                    <p class="text-xs text-stone-400">Min. stok: <?php echo e(rtrim(rtrim($bahan->stok_minimum, '0'), '.')); ?> <?php echo e($bahan->satuan); ?></p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">
                    <?php echo e(rtrim(rtrim($bahan->stok, '0'), '.')); ?> <?php echo e($bahan->satuan); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="px-5 py-8 text-center text-stone-400 text-sm">Semua stok aman.</div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-stone-200">
            <h2 class="font-semibold text-stone-900">Antrian Produksi</h2>
            <a href="<?php echo e(route('pesanan.index')); ?>" class="text-sm font-medium text-brand-600 hover:text-brand-700">Lihat semua &rarr;</a>
        </div>
        <div class="divide-y divide-stone-100">
            <?php $__empty_1 = true; $__currentLoopData = $pesananAntrian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="px-5 py-3.5 flex items-center justify-between cursor-pointer hover:bg-stone-50" onclick="window.location='<?php echo e(route('pesanan.show', $item)); ?>'">
                <div>
                    <p class="text-sm font-medium text-stone-900"><?php echo e($item->nomor_invoice); ?></p>
                    <p class="text-xs text-stone-400"><?php echo e($item->produk->nama_produk); ?> &middot; <?php echo e($item->nama_customer); ?></p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset <?php echo e($item->statusBadgeClass()); ?>"><?php echo e($item->statusLabel()); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="px-5 py-8 text-center text-stone-400 text-sm">Tidak ada antrian.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\NIzar Hasdian\Documents\fixads-laravel\fixads\resources\views/dashboard/production.blade.php ENDPATH**/ ?>