@extends('layouts.app')

@section('content')
<div class="section-header">
  <h1>Dashboard</h1>
</div>

<div class="row">
  @php
    $stats = [
      ['label' => 'Semua Barang', 'icon' => 'fas fa-cubes', 'color' => 'primary', 'value' => $barang],
      ['label' => 'Barang Masuk', 'icon' => 'fas fa-file-import', 'color' => 'danger', 'value' => $barangMasuk],
      ['label' => 'Barang Keluar', 'icon' => 'fas fa-file-export', 'color' => 'warning', 'value' => $barangKeluar],
      ['label' => 'Pengguna', 'icon' => 'far fa-user', 'color' => 'success', 'value' => $user],
    ];
  @endphp

  @foreach ($stats as $stat)
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-{{ $stat['color'] }}">
          <i class="{{ $stat['icon'] }}"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>{{ $stat['label'] }}</h4>
          </div>
          <div class="card-body">
            {{ $stat['value'] }}
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

<div class="row">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h4>Grafik Barang Masuk & Barang Keluar</h4>
      </div>
      <div class="card-body">
        <canvas id="summaryChart"></canvas>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h4>Stok Mencapai Batas Minimum</h4>
      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Stok</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($barangMinimum as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td><span class="badge badge-warning">{{ $item->stok }}</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('summaryChart').getContext('2d');

  const labels = {!! json_encode($labels) !!};
  const dataMasuk = {!! json_encode($barangMasukData->pluck('total')) !!};
  const dataKeluar = {!! json_encode($barangKeluarData->pluck('total')) !!};

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Barang Masuk',
          data: dataMasuk,
          backgroundColor: 'blue'
        },
        {
          label: 'Barang Keluar',
          data: dataKeluar,
          backgroundColor: 'red'
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              if (value % 1 === 0) {
                return value;
              }
            }
          }
        }
      }
    }
  });
</script>
@endpush
