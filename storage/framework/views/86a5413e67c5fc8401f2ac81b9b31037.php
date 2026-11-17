<style>
.menu-card-scroll {
    display: grid;
    grid-template-rows: repeat(2, auto);
    grid-auto-flow: column;
    grid-auto-columns: 160px;
    gap: 12px;
    overflow-x: auto;
    padding-bottom: 8px;
}
.menu-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 12px;
    cursor: pointer;
    background: #ffffff;
    color: #111827;
}
.menu-card:hover {
    background: #f9fafb;
}
.menu-card-img {
    width: 100%;
    aspect-ratio: 1 / 1;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 8px;
}
.menu-card-placeholder {
    width: 100%;
    aspect-ratio: 1 / 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    margin-bottom: 8px;
    background: #e5e7eb;
    color: #6b7280;
    font-size: 12px;
    text-align: center;
    padding: 8px;
}
.menu-empty {
    padding: 12px;
    color: #6b7280;
}
@media (prefers-color-scheme: dark) {
    .menu-card {
        background: #1f2937;
        color: #f9fafb;
        border-color: #374151;
    }
    .menu-card:hover {
        background: #374151;
    }
    .menu-card-placeholder {
        background: #374151;
        color: #9ca3af;
    }
    .menu-empty {
        color: #9ca3af;
    }
}
</style>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($menus->isEmpty()): ?>
    <div class="menu-empty">Menu tidak ditemukan</div>
<?php else: ?>
    <div class="menu-card-scroll">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <button
                type="button"
                wire:click="mountAction('addMenuToCart', { menuId: <?php echo e($menu->id); ?> }, { schemaComponent: 'form' })"
                class="menu-card"
            >
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($menu->image_url): ?>
                    <?php
                        $imageUrl = \Illuminate\Support\Str::startsWith($menu->image_url, 'http')
                            ? $menu->image_url
                            : \Illuminate\Support\Facades\Storage::disk('public')->url($menu->image_url);
                    ?>
                    <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($menu->name); ?>" class="menu-card-img" />
                <?php else: ?>
                    <div class="menu-card-placeholder"><?php echo e($menu->name); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <span style="font-weight: 600;"><?php echo e($menu->name); ?></span>
                <span style="font-size: 13px; color: #6b7280;">Rp <?php echo e(number_format($menu->price, 0, ',', '.')); ?></span>
            </button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /app/resources/views/filament/schemas/components/menu-cards.blade.php ENDPATH**/ ?>