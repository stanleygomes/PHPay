@extends('layouts.website')
@section('pageTitle', 'Ocorreu um erro...')

@section('content')

<div class="container pt-5 pb-5">
    <div class="row pt-5 pb-5">
        <div class="col-sm-12 pt-5 pb-5 text-center">
            <h1>
                <i class="fa fa-frown-open"></i>
                <div>
                    Erro 403.
                </div>
            </h1>
            <p>Este endereço não pode ser acessado.</p>
        </div>
    </div>
</div>

@endsection
