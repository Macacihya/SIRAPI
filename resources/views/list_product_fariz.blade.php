@extends('layouts.list_fariz')

@section('title', 'Ini adalah judul pada meta')

@section('content')
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Produk</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $post)
                <tr>
                    <td>{{ $post['id'] }}</td>
                    <td>{{ $post['produk'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection