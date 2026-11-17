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

@if ($menus->isEmpty())
    <div class="menu-empty">Menu tidak ditemukan</div>
@else
    <div class="menu-card-scroll">
        @foreach ($menus as $menu)
            <button
                type="button"
                wire:click="mountAction('addMenuToCart', { menuId: {{ $menu->id }} }, { schemaComponent: 'form' })"
                class="menu-card"
            >
                @if ($menu->image_url)
                    @php
                        $imageUrl = \Illuminate\Support\Str::startsWith($menu->image_url, 'http')
                            ? $menu->image_url
                            : \Illuminate\Support\Facades\Storage::disk('public')->url($menu->image_url);
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $menu->name }}" class="menu-card-img" />
                @else
                    <div class="menu-card-placeholder">{{ $menu->name }}</div>
                @endif
                <span style="font-weight: 600;">{{ $menu->name }}</span>
                <span style="font-size: 13px; color: #6b7280;">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
            </button>
        @endforeach
    </div>
@endif
