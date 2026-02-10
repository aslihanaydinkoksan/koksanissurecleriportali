@extends('layouts.app')

@section('title', 'Kanban Panosu Yönetimi')
@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">İş Süreçleri Panoları (Kanban)</h1>
            <a href="{{ route('kanban-boards.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Yeni Pano Oluştur
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Mevcut Panolar</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Pano Adı</th>
                                <th>Pano Sahibi</th>
                                <th>Fabrika / Birim</th>
                                <th>Modül</th>
                                <th>Sütun Sayısı</th>
                                <th class="text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($boards as $board)
                                <tr>
                                    <td class="font-weight-bold">{{ $board->name }}</td>
                                    <td>{{ $board->user->name ?? 'Sistem' }}</td>
                                    <td>
                                        <span class="badge bg-info text-white">
                                            {{ $board->businessUnit->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>{{ strtoupper($board->module_scope) }}</td>
                                    <td>{{ $board->columns->count() }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('kanban.board', ['board_id' => $board->id]) }}"
                                            class="btn btn-sm btn-info text-white me-1" target="_blank">
                                            <i class="fa fa-columns"></i> Panoya Git
                                        </a>
                                        <a href="{{ route('kanban-boards.edit', $board->id) }}"
                                            class="btn btn-sm btn-warning text-white">
                                            Panoyu Düzenle
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
