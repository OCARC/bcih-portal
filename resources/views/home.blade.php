@extends('common.master')
@section('title')
Overview
	@endsection
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Status</div>

				<table class="table table-striped table-condensed">

					<thead>
					<tr>
						<th>Site</th>
					</tr>
					</thead>
					<tbody>
					@foreach ($sites as $site)
						<tr>
						<td><a href="/site/{{ $site->id }}">{{$site->name}}</a></td>
							@foreach ($site->equipment as $equipment)
								<td class="text-center" style="background-color: {{ $equipment->getHealthColor() }}"><a href="{{ url( "/equipment/" . $equipment->id ) }}">{{$equipment->hostname}}</a><br>{{ $equipment->getHealthStatus() }}</td>
							@endforeach

						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
