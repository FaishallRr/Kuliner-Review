<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<style>*{font-family:'Inter',system-ui,-apple-system,sans-serif}.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}@keyframes fadeIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}.animate-fade-in{animation:fadeIn .3s ease-out}.star-rating input:checked~label,.star-rating label:hover,.star-rating label:hover~label{color:#f59e0b}.star-rating input:checked+label{color:#f59e0b}</style>
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6 animate-fade-in">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center shadow-md">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Edit Ulasan</h1>
    </div>

    <div class="bg-amber-50 border border-amber-200/60 rounded-2xl p-4 mb-6 flex items-center gap-3 animate-fade-in">
        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <p class="text-sm text-amber-700">Ulasan hanya bisa diedit dalam 24 jam sejak dibuat.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 animate-fade-in">
        <form action="/reviews/<?= $review['id'] ?>/update" method="POST">
            <?= csrf_field() ?>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Rating</label>
                <div class="star-rating flex items-center gap-2">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" <?= $review['rating'] == $i ? 'checked' : '' ?> class="hidden peer" required>
                    <?php endfor; ?>
                    <div class="flex gap-1.5">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <label for="star<?= $i ?>" class="cursor-pointer p-1 rounded-lg transition-colors hover:bg-amber-50" title="<?= $i ?>">
                            <svg class="w-8 h-8 transition-colors <?= $review['rating'] >= $i ? 'text-amber-400' : 'text-gray-200' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </label>
                        <?php endfor; ?>
                    </div>
                </div>
                <script>
                    document.querySelectorAll('.star-rating input').forEach(function(input) {
                        input.addEventListener('change', function() {
                            var val = parseInt(this.value);
                            document.querySelectorAll('.star-rating label svg').forEach(function(svg, idx) {
                                svg.classList.toggle('text-amber-400', idx < val);
                                svg.classList.toggle('text-gray-200', idx >= val);
                            });
                        });
                    });
                </script>
            </div>

            <div class="mb-6">
                <label for="comment" class="block text-sm font-semibold text-gray-700 mb-2">Komentar</label>
                <textarea name="comment" id="comment" rows="4" placeholder="Tulis pengalaman kuliner kamu..."
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all resize-none text-gray-700 placeholder-gray-300"><?= esc($review['comment'] ?? '') ?></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white px-6 py-2.5 rounded-xl font-semibold hover:from-orange-600 hover:to-amber-600 transition-all shadow-sm shadow-orange-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
                <a href="/places/<?= $review['place_id'] ?>" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-6 py-2.5 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>