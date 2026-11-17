@extends('layouts.app')
@section('title', 'Kontak & Lokasi')
@section('description', 'Alamat, jam operasional, dan formulir kontak '.setting('brand_name').'.')

@section('content')
@include('partials.page-header', ['heading' => 'Kontak & Lokasi', 'crumbs' => ['Kontak' => null], 'lead' => 'Punya pertanyaan, kritik, atau rencana acara? Kirim pesan kepada kami.'])

@php $hours = parse_hours(setting('opening_hours')); @endphp
<section id="contact-section" class="pgbody">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-right">
                <div class="ctdark">
                    <h4>Informasi</h4>
                    <p class="ctsub">Pesan ini hanya untuk komunikasi, bukan untuk memesan makanan.</p>
                    @if(setting('address'))
                    <div class="ctitem">
                        <div class="cticon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="ctinfo"><strong>Alamat</strong><span>{!! nl2br(e(setting('address'))) !!}</span></div>
                    </div>
                    @endif
                    @if(setting('phone'))
                    <div class="ctitem">
                        <div class="cticon"><i class="fas fa-phone-alt"></i></div>
                        <div class="ctinfo"><strong>Telepon</strong><span>{{ setting('phone') }}</span></div>
                    </div>
                    @endif
                    @if(setting('email'))
                    <div class="ctitem">
                        <div class="cticon"><i class="fas fa-envelope"></i></div>
                        <div class="ctinfo"><strong>Email</strong><span>{{ setting('email') }}</span></div>
                    </div>
                    @endif
                    @if($hours)
                    <div class="ctitem">
                        <div class="cticon"><i class="fas fa-clock"></i></div>
                        <div class="ctinfo"><strong>Jam operasional</strong><span>@foreach($hours as $h){{ $h['label'] }}@if($h['time']): {{ $h['time'] }}@endif<br/>@endforeach</span></div>
                    </div>
                    @endif
                    @if(social_links())
                    <div class="ctsocrow">
                        @foreach(social_links() as $s)
                            <a href="{{ $s['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $s['label'] }}"><i class="{{ $s['icon'] }}"></i></a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-8" data-aos="fade-left">
                <div class="formcard" id="form">
                    @if(session('success'))
                        <div class="formalert ok" role="status"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="formalert err" role="alert"><i class="fas fa-circle-exclamation"></i> Pesan belum terkirim. Periksa isian yang ditandai.</div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="flbl" for="c-name">Nama *</label>
                                <input id="c-name" type="text" name="name" class="fctrl @error('name') is-bad @enderror" value="{{ old('name') }}" maxlength="100" required>
                                @error('name')<div class="ferr">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="flbl" for="c-email">Email *</label>
                                <input id="c-email" type="email" name="email" class="fctrl @error('email') is-bad @enderror" value="{{ old('email') }}" maxlength="150" required>
                                @error('email')<div class="ferr">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="flbl" for="c-phone">Nomor telepon</label>
                                <input id="c-phone" type="tel" name="phone" class="fctrl @error('phone') is-bad @enderror" value="{{ old('phone') }}" maxlength="40">
                                @error('phone')<div class="ferr">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="flbl" for="c-subject">Subjek *</label>
                                <select id="c-subject" name="subject" class="fctrl @error('subject') is-bad @enderror" required>
                                    @foreach(\App\Http\Requests\ContactRequest::SUBJECTS as $s)
                                        <option value="{{ $s }}" @selected(old('subject') === $s)>{{ $s }}</option>
                                    @endforeach
                                </select>
                                @error('subject')<div class="ferr">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="flbl" for="c-message">Pesan *</label>
                                <textarea id="c-message" name="message" rows="5" class="fctrl @error('message') is-bad @enderror" maxlength="3000" required>{{ old('message') }}</textarea>
                                @error('message')<div class="ferr">{{ $message }}</div>@enderror
                            </div>
                            {{-- Honeypot: disembunyikan dari pengguna; bot biasanya mengisinya. --}}
                            <div class="hp-field" aria-hidden="true">
                                <label for="c-website">Jangan diisi</label>
                                <input id="c-website" type="text" name="website" tabindex="-1" autocomplete="off">
                            </div>
                            <div class="col-12"><button class="btn-red" type="submit"><i class="fas fa-paper-plane"></i>Kirim Pesan</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($locations->isNotEmpty())
            <div class="text-center mt-5 pt-4 mb-4">
                <span class="slbl">Lokasi</span>
                <h3 class="stitle">Temukan Kami</h3>
                <div class="sline"></div>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach($locations as $loc)
                    <div class="col-md-6 col-lg-4">
                        <div class="loccard">
                            <h5>{{ $loc->name }}</h5>
                            <p><i class="fas fa-location-dot"></i>{!! nl2br(e($loc->address)) !!}</p>
                            @if($loc->phone)<p><i class="fas fa-phone"></i>{{ $loc->phone }}</p>@endif
                            @if($loc->opening_hours)<p><i class="fas fa-clock"></i>{!! nl2br(e($loc->opening_hours)) !!}</p>@endif
                            @if($loc->map_url)<a href="{{ $loc->map_url }}" target="_blank" rel="noopener noreferrer" class="blmore">Buka di peta <i class="fas fa-arrow-right"></i></a>@endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
