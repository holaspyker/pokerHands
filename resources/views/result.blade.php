@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Result</div>
                    <div class="card-body">
                        <?php
                        $wins_hands = \App\Wins::getWinners()
                        ?>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Player</th>
                                <th scope="col">wins</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($wins_hands as $hand)
                                <tr>
                                    <td> name {{$hand->player}}</td>
                                    <td>  {{$hand->total}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
