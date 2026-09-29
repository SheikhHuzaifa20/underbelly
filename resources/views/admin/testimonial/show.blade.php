@extends('layouts.app')

@section('content')
    <div class="container-fluid bg-white mt-5">
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box card">
                    <div class="card-body">
                        <h3 class="box-title pull-left">Testimonial {{ $testimonial->id }}</h3>
                        @can('view-' . str_slug('Testimonial'))
                            <a class="btn btn-success pull-right" href="{{ route('admin.testimonial.index') }}">
                                <i class="icon-arrow-left-circle" aria-hidden="true"></i> Back
                            </a>
                        @endcan
                        <div class="clearfix"></div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table">
                                <tbody>
                                    <tr>
                                        <th>ID</th>
                                        <td>{{ $testimonial->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Title</th>
                                        <td>{{ $testimonial->title }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.admin.footer')
    </div>
@endsection