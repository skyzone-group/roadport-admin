@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('cruds.cars.title')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('global.home')</a></li>
                        <li class="breadcrumb-item active">@lang('cruds.cars.title')</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">@lang('cruds.cars.title_singular')</h3>
                        <a href="{{ route('carsAdd') }}" class="btn btn-success btn-sm float-right">
                            <span class="fas fa-plus-circle"></span>
                            @lang('global.add')
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!-- Data table -->
                        <table id="dataTable" class="table table-bordered table-striped dataTable dtr-inline table-responsive-lg" role="grid" aria-describedby="dataTable_info">
                            <thead>
                            <tr>
                                <th>@lang('cruds.cars.fields.created_at')</th>
                                <th>@lang('cruds.cars.fields.name_uz')</th>
                                <th>@lang('cruds.cars.fields.name_ru')</th>
                                <th>@lang('cruds.cars.fields.comment_uz')</th>
                                <th>@lang('cruds.cars.fields.comment_ru')</th>
                                <th class="w-25">@lang('global.actions')</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($cars as $item)
                                    <tr>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->name_uz }}</td>
                                        <td>{{ $item->name_ru }}</td>
                                        <td>{{ $item->description_uz ?? __('cruds.cars.fields.not_comment') }}</td>
                                        <td>{{ $item->description_ru ?? __('cruds.cars.fields.not_comment') }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('carDestroy', $item->id) }}" method="post">
                                                @csrf
                                                <div class="btn-group">
                                                    <a href="{{ route('carEdit', $item->id) }}" type="button" class="btn btn-info btn-sm"> @lang('global.edit')</a>
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="if (confirm('Вы уверены?')) {this.form.submit()}"> @lang('global.delete')</button>
                                                </div>
                                            </form>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection
