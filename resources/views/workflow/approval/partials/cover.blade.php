<style>
    .cover-page { position: relative; height: 765px; page-break-after: always; text-align: center; font-family: DejaVu Sans, sans-serif; color: #111; }
    .cover-headings { padding-top: 76px; font-size: 13px; line-height: 1.35; font-weight: bold; text-transform: uppercase; }
    .cover-institution { margin-top: 10px; font-size: 23px; line-height: 1.25; font-weight: bold; text-transform: uppercase; }
    .cover-logo { display: block; width: 105px; height: 120px; margin: 85px auto 65px; object-fit: contain; }
    .cover-title { margin-top: 0; font-size: 23px; line-height: 1.4; font-weight: bold; text-transform: uppercase; }
    .cover-year { margin-top: 28px; font-size: 22px; font-weight: bold; }
    .cover-address { position: absolute; right: 20px; bottom: 7px; left: 20px; font-size: 11px; line-height: 1.5; font-weight: bold; }
</style>
@php($coverFirstRkbu = $items->first()['rkbu'] ?? null)
<section class="cover-page">
    <div class="cover-headings">
        @foreach (collect([$judulHeader?->header1, $judulHeader?->header2, $judulHeader?->header3, $judulHeader?->header4, $judulHeader?->header5, $judulHeader?->header6])->filter() as $coverHeading)
            <div>{{ $coverHeading }}</div>
        @endforeach
    </div>
    <div class="cover-institution">{{ $judulHeader?->nama_rs ?? 'Instansi' }}</div>
    @if ($coverLogo)<img class="cover-logo" src="{{ $coverLogo }}" alt="Lambang instansi">@endif
    <div class="cover-title">RENCANA BISNIS DAN ANGGARAN</div>
    <div class="cover-year">TAHUN ANGGARAN {{ $coverFirstRkbu?->tahunAnggaran?->nama_tahun_anggaran ?? $coverFirstRkbu?->tahunAnggaran?->tahun ?? now()->year }}</div>
    <div class="cover-address">
        @if ($judulHeader?->alamat_rs)<div>{{ $judulHeader->alamat_rs }}</div>@endif
        @if ($judulHeader?->tlp_rs)<div>Telepon: {{ $judulHeader->tlp_rs }}</div>@endif
    </div>
</section>
