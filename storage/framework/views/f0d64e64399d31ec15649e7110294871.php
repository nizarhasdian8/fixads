<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-stone-900">Dashboard</h1>
    <p class="text-stone-500 text-sm mt-1">Ringkasan pesanan dan aktivitas hari ini.</p>
</div>


<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    
    <a href="<?php echo e(route('pesanan.index')); ?>" class="bg-white border border-stone-200 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Pesanan Masuk</p>
            <p class="text-3xl font-bold text-stone-900 mt-1"><?php echo e($pesananMasukHariIni); ?></p>
            <p class="text-xs text-stone-500">Transaksi hari ini</p>
        </div>
    </a>

    
    <a href="<?php echo e(route('pesanan.index', ['status' => 'completed'])); ?>" class="bg-white border border-stone-200 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Produksi Selesai</p>
            <p class="text-3xl font-bold text-stone-900 mt-1"><?php echo e($siapDiambil); ?></p>
            <p class="text-xs text-stone-500">Selesai</p>
        </div>
    </a>

    
    <a href="<?php echo e(route('pesanan.index', ['status' => 'diterima'])); ?>" class="bg-white border border-stone-200 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Diterima Pelanggan</p>
            <p class="text-3xl font-bold text-stone-900 mt-1"><?php echo e($diterimaPelanggan); ?></p>
            <p class="text-xs text-stone-500">Pesanan selesai</p>
        </div>
    </a>
</div>

<?php if($permintaanPending > 0): ?>
<div class="mb-6 flex items-center justify-between bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <p class="text-sm font-medium text-amber-800"><?php echo e($permintaanPending); ?> permintaan bahan baku menunggu persetujuan Anda.</p>
    </div>
    <a href="<?php echo e(route('pengajuan.index', ['status' => 'pending'])); ?>" class="text-sm font-semibold text-amber-700 hover:text-amber-900 shrink-0">Lihat &rarr;</a>
</div>
<?php endif; ?>


<div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-stone-200">
        <h2 class="font-semibold text-stone-900">Pesanan Terbaru (Hari Ini)</h2>
        <a href="<?php echo e(route('pesanan.index')); ?>" class="text-sm font-medium text-brand-600 hover:text-brand-700">Lihat semua &rarr;</a>
    </div>
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
                <?php $__empty_1 = true; $__currentLoopData = $pesananTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                <tr><td colspan="9" class="px-5 py-8 text-center text-stone-400">Belum ada pesanan hari ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\NIzar Hasdian\Documents\fixads-laravel\fixads\resources\views/dashboard/marketing.blade.php ENDPATH**/ ?>