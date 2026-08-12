<?php $__env->startSection('title', $pesanan->nomor_invoice); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('pesanan.index')); ?>" class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-900 mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Kembali
    </a>
    <div class="flex flex-wrap items-center gap-3">
        <h1 class="text-2xl font-bold text-stone-900"><?php echo e($pesanan->nomor_invoice); ?></h1>
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset <?php echo e($pesanan->statusBadgeClass()); ?>"><?php echo e($pesanan->statusLabel()); ?></span>
    </div>
    <p class="text-stone-500 text-sm mt-1">Dibuat oleh <?php echo e($pesanan->pembuat->name); ?> &middot; <?php echo e($pesanan->created_at->format('d M Y, H:i')); ?></p>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-4">Detail Pesanan</h2>
            <dl class="grid sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                <div>
                    <dt class="text-stone-400">Nama Customer</dt>
                    <dd class="text-stone-900 font-medium mt-0.5"><?php echo e($pesanan->nama_customer); ?></dd>
                </div>
                <div>
                    <dt class="text-stone-400">Nomor HP</dt>
                    <dd class="text-stone-900 font-medium mt-0.5"><?php echo e($pesanan->nomor_hp); ?></dd>
                </div>
                <div>
                    <dt class="text-stone-400">Sumber Pesanan</dt>
                    <dd class="text-stone-900 font-medium mt-0.5"><?php echo e($pesanan->sumber_pesanan); ?></dd>
                </div>
                <div>
                    <dt class="text-stone-400">Deadline</dt>
                    <dd class="text-stone-900 font-medium mt-0.5"><?php echo e($pesanan->deadline->format('d M Y')); ?></dd>
                </div>
                <div>
                    <dt class="text-stone-400">Jenis Produk</dt>
                    <dd class="text-stone-900 font-medium mt-0.5"><?php echo e($pesanan->produk->nama_produk); ?></dd>
                </div>
                <div>
                    <dt class="text-stone-400">Ukuran</dt>
                    <dd class="text-stone-900 font-medium mt-0.5"><?php echo e($pesanan->ukuran); ?></dd>
                </div>
                <div>
                    <dt class="text-stone-400">Jumlah</dt>
                    <dd class="text-stone-900 font-medium mt-0.5"><?php echo e($pesanan->jumlah); ?> pcs</dd>
                </div>
                <div>
                    <dt class="text-stone-400">Harga</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">Rp <?php echo e(number_format($pesanan->harga, 0, ',', '.')); ?></dd>
                </div>
                <div>
                    <dt class="text-stone-400">Kode Teknisi</dt>
                    <dd class="text-stone-900 font-medium mt-0.5"><?php echo e($pesanan->kode_teknisi ?? '—'); ?></dd>
                </div>
                <?php if($pesanan->spesifikasi): ?>
                <div class="sm:col-span-2">
                    <dt class="text-stone-400">Spesifikasi</dt>
                    <dd class="text-stone-700 mt-0.5"><?php echo e($pesanan->spesifikasi); ?></dd>
                </div>
                <?php endif; ?>
                <?php if($pesanan->catatan): ?>
                <div class="sm:col-span-2">
                    <dt class="text-stone-400">Catatan</dt>
                    <dd class="text-stone-700 mt-0.5"><?php echo e($pesanan->catatan); ?></dd>
                </div>
                <?php endif; ?>
            </dl>

            <?php if($pesanan->file_desain): ?>
            <div class="mt-5 pt-5 border-t border-stone-100">
                <dt class="text-stone-400 text-sm mb-2">File Desain</dt>
                <a href="<?php echo e(asset('storage/'.$pesanan->file_desain)); ?>" target="_blank" class="inline-block">
                    <img src="<?php echo e(asset('storage/'.$pesanan->file_desain)); ?>" alt="Desain pesanan" class="max-w-xs rounded-xl border border-stone-200 hover:opacity-90 transition">
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="space-y-6">
        
        
        <?php if(auth()->user()->isProduction() && in_array($pesanan->status, ['queue', 'processing', 'delayed'])): ?>
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <form method="POST" action="<?php echo e(route('pesanan.update-status', $pesanan)); ?>" x-data="{ rows: [{ bahan_baku_id: '', jumlah_pakai: '' }] }">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <h2 class="font-semibold text-stone-900 mb-4">Update Produksi</h2>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Kode Teknisi</label>
                    <input type="text" name="kode_teknisi" value="<?php echo e(old('kode_teknisi', $pesanan->kode_teknisi)); ?>" placeholder="cth. T01"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition <?php $__errorArgs = ['kode_teknisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['kode_teknisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600 mt-1">Harap isi kode teknisi.</p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Status Pesanan</label>
                    <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                        <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(in_array($value, ['queue', 'processing', 'delayed', 'completed'])): ?>
                        <option value="<?php echo e($value); ?>" <?php if($pesanan->status === $value): echo 'selected'; endif; ?> <?php if(in_array($pesanan->status, ['processing', 'delayed']) && $value === 'queue'): echo 'disabled'; endif; ?>><?php echo e($label); ?></option>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="mb-2 flex items-center justify-between">
                    <label class="block text-sm font-medium text-stone-700">Pemakaian Bahan Baku</label>
                </div>
                <p class="text-xs text-stone-400 mb-3">Isi bahan baku yang dipakai untuk pesanan ini. Stok akan berkurang otomatis setelah disimpan.</p>

                
                <?php $__errorArgs = ['bahan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600 mb-3"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <template x-for="(row, index) in rows" :key="index">
                    <div class="flex gap-2 mb-2">
                        <select :name="'bahan[' + index + '][bahan_baku_id]'" x-model="row.bahan_baku_id" class="flex-1 px-3 py-2 rounded-lg border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                            <option value="">Pilih bahan...</option>
                            <?php $__currentLoopData = $bahanBakuList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bahan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($bahan->id); ?>"><?php echo e($bahan->nama); ?> (<?php echo e(rtrim(rtrim($bahan->stok, '0'), '.')); ?> <?php echo e($bahan->satuan); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <input type="number" step="0.01" min="0.01" :name="'bahan[' + index + '][jumlah_pakai]'" x-model="row.jumlah_pakai" placeholder="Jumlah" class="w-24 px-3 py-2 rounded-lg border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                        <button type="button" @click="rows.splice(index, 1)" class="px-2.5 text-stone-400 hover:text-red-600 transition" x-show="rows.length > 1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </template>
                <button type="button" @click="rows.push({ bahan_baku_id: '', jumlah_pakai: '' })" class="text-sm font-medium text-brand-600 hover:text-brand-700 mb-5">+ Tambah bahan</button>

                <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm py-2.5 rounded-xl transition shadow-sm shadow-brand-500/20">Update</button>
            </form>
        </div>

        
        <?php if($pesanan->pemakaianBahan->isNotEmpty()): ?>
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-4">Informasi Pemakaian Bahan Baku</h2>
            <div class="divide-y divide-stone-100">
                <?php $__currentLoopData = $pesanan->pemakaianBahan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pakai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="py-2.5 flex items-center justify-between text-sm">
                    <span class="text-stone-700"><?php echo e($pakai->bahanBaku->nama); ?></span>
                    <span class="font-medium text-stone-900"><?php echo e(floatval($pakai->jumlah_pakai)); ?> <?php echo e($pakai->bahanBaku->satuan); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        
        <?php elseif(auth()->user()->isProduction() && $pesanan->status === 'completed'): ?>
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-2">Update Produksi</h2>
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <p class="text-sm text-gray-600">Status pesanan sudah <span class="font-semibold">Selesai Produksi</span>. Menunggu CIO Marketing untuk mengubah status menjadi Diterima Pelanggan.</p>
            </div>
        </div>
        
        
        <?php if($pesanan->pemakaianBahan->isNotEmpty()): ?>
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-4">Informasi Pemakaian Bahan Baku</h2>
            <div class="divide-y divide-stone-100">
                <?php $__currentLoopData = $pesanan->pemakaianBahan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pakai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="py-2.5 flex items-center justify-between text-sm">
                    <span class="text-stone-700"><?php echo e($pakai->bahanBaku->nama); ?></span>
                    <span class="font-medium text-stone-900"><?php echo e(floatval($pakai->jumlah_pakai)); ?> <?php echo e($pakai->bahanBaku->satuan); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        
        <?php elseif(auth()->user()->isMarketing() && $pesanan->status === 'completed'): ?>
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-4">Update Status</h2>
            <form method="POST" action="<?php echo e(route('pesanan.update-status', $pesanan)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="status" value="diterima">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-green-900">Produksi telah selesai.</p>
                        <p class="text-sm text-green-700">Klik tombol di bawah jika barang sudah diterima oleh pelanggan.</p>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        Diterima Pelanggan
                    </button>
                </div>
            </form>
        </div>

        
        <?php elseif(auth()->user()->isMarketing() && in_array($pesanan->status, ['queue', 'processing', 'delayed'])): ?>
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-2">Update Status</h2>
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <?php if($pesanan->status === 'queue'): ?>
                <p class="text-sm text-gray-600">Pesanan masih dalam antrian. Tunggu hingga CIO Production memproses pesanan ini.</p>
                <?php elseif($pesanan->status === 'processing'): ?>
                <p class="text-sm text-gray-600">Pesanan sedang dalam proses produksi. Tunggu hingga CIO Production menyelesaikan produksinya.</p>
                <?php elseif($pesanan->status === 'delayed'): ?>
                <p class="text-sm text-gray-600">Proses produksi pesanan tertunda. Silakan koordinasi dengan CIO Production terkait kendala yang terjadi.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($pesanan->permintaanBahan->isNotEmpty()): ?>
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-4">Permintaan Bahan Terkait</h2>
            <div class="divide-y divide-stone-100">
                <?php $__currentLoopData = $pesanan->permintaanBahan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('pengajuan.show', $p)); ?>" class="py-2.5 flex items-center justify-between text-sm hover:bg-stone-50 -mx-2 px-2 rounded-lg">
                    <span class="text-stone-700"><?php echo e($p->nomor_permintaan); ?></span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset <?php echo e($p->statusBadgeClass()); ?>"><?php echo e($p->statusLabel()); ?></span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\NIzar Hasdian\Documents\fixads-laravel\fixads\resources\views/pesanan/show.blade.php ENDPATH**/ ?>