@php $hours = parse_hours(setting('opening_hours')); @endphp
<footer>
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="fnm">{{ brand_wordmark() }}</div>
                <p class="fdesc">{{ setting('brand_description') }}</p>
                <div class="fsoc">
                    @foreach(social_links() as $s)
                        <a href="{{ $s['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $s['label'] }}"><i class="{{ $s['icon'] }}"></i></a>
                    @endforeach
                </div>
            </div>
            <div class="col-sm-6 col-lg-2">
                <div class="ftit">Tautan</div>
                <ul class="flinks ps-0">
                    <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right"></i>Beranda</a></li>
                    <li><a href="{{ route('about') }}"><i class="fas fa-chevron-right"></i>Tentang Kami</a></li>
                    <li><a href="{{ route('menu.index') }}"><i class="fas fa-chevron-right"></i>Menu</a></li>
                    <li><a href="{{ route('articles.index') }}"><i class="fas fa-chevron-right"></i>Artikel</a></li>
                    <li><a href="{{ route('gallery') }}"><i class="fas fa-chevron-right"></i>Galeri</a></li>
                    <li><a href="{{ route('contact') }}"><i class="fas fa-chevron-right"></i>Kontak</a></li>
                </ul>
            </div>
            <div class="col-sm-6 col-lg-2">
                <div class="ftit">Menu Kami</div>
                <ul class="flinks ps-0">
                    @foreach($navCategories as $c)
                        <li><a href="{{ route('menu.index', ['kategori' => $c->slug]) }}"><i class="fas fa-chevron-right"></i>{{ $c->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-4">
                <div class="ftit">Hubungi Kami</div>
                @if(setting('address'))
                <div class="fci">
                    <div class="fciico"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="fciinfo"><strong>Alamat</strong>{{ setting('address') }}</div>
                </div>
                @endif
                @if(setting('phone'))
                <div class="fci">
                    <div class="fciico"><i class="fas fa-phone-alt"></i></div>
                    <div class="fciinfo"><strong>Telepon</strong>{{ setting('phone') }}</div>
                </div>
                @endif
                @if(setting('email'))
                <div class="fci">
                    <div class="fciico"><i class="fas fa-envelope"></i></div>
                    <div class="fciinfo"><strong>Email</strong>{{ setting('email') }}</div>
                </div>
                @endif
                @if($hours)
                <div class="fci">
                    <div class="fciico"><i class="fas fa-clock"></i></div>
                    <div class="fciinfo"><strong>Jam operasional</strong>
                        @foreach($hours as $h){{ $h['label'] }}@if($h['time']): {{ $h['time'] }}@endif<br/>@endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="fbot">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <p>&copy; {{ date('Y') }} <span>{{ setting('brand_name') }}</span>. {{ setting('footer_text') }}</p>
                <div><a href="{{ route('privacy') }}">Kebijakan Privasi</a><a href="{{ route('contact') }}">Kontak</a></div>
            </div>
        </div>
    </div>
</footer>
