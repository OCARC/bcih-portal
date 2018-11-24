@extends('common.wide')
@section('title')
Overview
	@endsection
@section('content')
	<style>

	</style>

	<div class="container-fluid site-container">
		@foreach ($sites as $site)
			@if ($site->status == "Potential" or $site->status == "Planning" or $site->status == "No Install")
				@continue
			@endif
			<div class="row" style="">
				<div class="col-lg-2 col-md-2">
					<div class="nameplate">
						<a href="/site/{{ $site->id }}">{{$site->name}}</a>
					</div>
					@php( $clients = 0 )
					@foreach ($site->equipment as $equipment)
						@php( $clients += $equipment->libre_wireless('clients')->sensor_current )
					@endforeach

					{{--@if ($site->status == "Potential")--}}
						{{--<div class="site-status" style="margin-bottom: 5px;    text-align: center;    font-size: 11px;background-color: #e1e1e1">{{ $site->status }}</div>--}}
					{{--@elseif( $site->status == "Planning")--}}
						{{--<div class="site-status" style="margin-bottom: 5px;    text-align: center;    font-size: 11px;background-color: #fff6a6">{{ $site->status }}</div>--}}
					{{--@elseif( $site->status == "Installed")--}}
						{{--<div class="site-status" style="margin-bottom: 5px;    text-align: center;    font-size: 11px;background-color: #aaffaa">{{ $site->status }}</div>--}}
					{{--@elseif( $site->status == "Equip Failed")--}}
						{{--<div class="site-status" style="margin-bottom: 5px;    text-align: center;    font-size: 11px;background-color: #ff6666">{{ $site->status }}</div>--}}
					{{--@elseif( $site->status == "Problems")--}}
						{{--<div class="site-status" style="margin-bottom: 5px;    text-align: center;    font-size: 11px;background-color: #ffd355">{{ $site->status }}</div>--}}
					{{--@elseif( $site->status == "No Install")--}}
						{{--<div class="site-status" style="margin-bottom: 5px;    text-align: center;    font-size: 11px;background-color: #979797">{{ $site->status }}</div>--}}
					{{--@else--}}
						{{--<div class="site-status" style="margin-bottom: 5px;    text-align: center;    font-size: 11px;">{{ $site->status }}</div>--}}
					{{--@endif--}}
					<div class="site-connections"><strong>Clients:</strong> {{ $clients }}</div>

					<div class="site-description">{{$site->description}}</div>
				</div>

			<div class="col-lg-8">
				@foreach ($site->equipment as $equipment)
					<div class="text-center equipment-block" style=" border-color: {{ $equipment->getHealthColor() }}">

						<a href="{{ url( "/equipment/" . $equipment->id ) }}" style="font-size: 11px;">
							@if( $equipment->libre_wireless('clients')->sensor_current >= 1 )
							<div class="tl-corner-badge" style=" border-color: {{ $equipment->getHealthColor() }}">
								{{$equipment->libre_wireless('clients')->sensor_current}}
							</div>
							@endif
							{{--<div class="text-center" style="width: 126px; display:inline-block; background-color: {{ $equipment->getHealthColor() }}">--}}

							{{--{{ $equipment->getHealthStatus() }}</div>--}}
							<img src="{{$equipment->icon() }}" style="width:64px; height: 64px;"><br>
								{!! str_replace('.IF.HAMWAN.CA', '', strtoupper( $equipment->hostname)) !!}</a><br>

					</div>
				@endforeach
				</div>

				<div class="col-lg-2  hidden-md  hidden-sm  hidden-xs">
					@foreach ($site->equipment as $equipment)
						@if ( substr($equipment->hostname,0,4) == 'HEX1' )
							<div class="equipment-graph">
					<img style="width: 100%; height: 100px" src="http://portal.hamwan.ca/librenms/graph.php?height=88&width=280&device={{ $equipment->librenms_mapping }}&type=device_bits&from=-7D&legend=no&popup_title=&bg=FFFFFF00">
							</div>
						@endif
							@if ( substr($equipment->hostname,0,6) == 'ROUTER' )
								<div class="equipment-graph">
									<img style="width: 100%; height: 100px" src="http://portal.hamwan.ca/librenms/graph.php?height=88&width=280&device={{ $equipment->librenms_mapping }}&type=device_bits&from=-7D&legend=no&popup_title=&bg=FFFFFF00">
								</div>
							@endif
						@endforeach
				</div>
			</div>

		@endforeach
	</div>

		{{--<div class="container">--}}
	{{--<div class="row">--}}
		{{--<div class="col-md-10 col-md-offset-1">--}}
			{{--<div class="panel panel-default">--}}
				{{--<div class="panel-heading">Status</div>--}}
				{{--<div class="table-responsive">--}}

				{{--<table class="table sortable table-responsive table-condensed table-striped table-bordered">--}}

					{{--<thead>--}}
					{{--<tr>--}}
						{{--<th>Site</th>--}}
						{{--<th>Equipment</th>--}}
					{{--</tr>--}}
					{{--</thead>--}}
					{{--<tbody>--}}
					{{--@foreach ($sites as $site)--}}
						{{--<tr>--}}
						{{--<td style="max-width: 140px;">--}}
							{{--@if ($site->status == "Potential")--}}
								{{--<div style="    margin: -5px;    margin-bottom: 5px;    text-align: center;    font-size: 11px;background-color: #e1e1e1">{{ $site->status }}</div>--}}
							{{--@elseif( $site->status == "Planning")--}}
								{{--<div style="    margin: -5px;    margin-bottom: 5px;    text-align: center;    font-size: 11px;background-color: #fff6a6">{{ $site->status }}</div>--}}
							{{--@elseif( $site->status == "Installed")--}}
								{{--<div style="    margin: -5px;    margin-bottom: 5px;    text-align: center;    font-size: 11px;background-color: #aaffaa">{{ $site->status }}</div>--}}
							{{--@elseif( $site->status == "Equip Failed")--}}
								{{--<div style="    margin: -5px;    margin-bottom: 5px;    text-align: center;    font-size: 11px;background-color: #ff6666">{{ $site->status }}</div>--}}
							{{--@elseif( $site->status == "Problems")--}}
								{{--<div style="    margin: -5px;    margin-bottom: 5px;    text-align: center;    font-size: 11px;background-color: #ffd355">{{ $site->status }}</div>--}}
							{{--@elseif( $site->status == "No Install")--}}
								{{--<div style="    margin: -5px;    margin-bottom: 5px;    text-align: center;    font-size: 11px;background-color: #979797">{{ $site->status }}</div>--}}
							{{--@else--}}
								{{--<div style="    margin: -5px;    margin-bottom: 5px;    text-align: center;    font-size: 11px;">{{ $site->status }}</div>--}}
							{{--@endif--}}
							{{--<a href="/site/{{ $site->id }}" style="font-size: 11px;">{{$site->name}}</a>--}}

							{{--<div style="font-size: 10px;">{{$site->description}}</div>--}}
						{{--</td>--}}
							{{--<td>--}}
							{{--@foreach ($site->equipment as $equipment)--}}
								{{--<div class="text-center" style="width: 100px; overflow:hidden; display:inline-block; border-radius: 1em; border: 2px solid {{ $equipment->getHealthColor() }}">--}}
									{{--<a href="{{ url( "/equipment/" . $equipment->id ) }}" style="font-size: 11px;">--}}
										{{--<div class="text-center" style="width: 126px; display:inline-block; background-color: {{ $equipment->getHealthColor() }}">--}}

											{{--{{ $equipment->getHealthStatus() }}</div>--}}
									{{--<img src="{{$equipment->icon() }}" style="width:64px; height: 64px;"><br>--}}
										{{--{{$equipment->hostname}}</a><br>--}}

								{{--</div>--}}
							{{--@endforeach--}}
							{{--</td>--}}
						{{--</tr>--}}
						{{--@endforeach--}}
					{{--</tbody>--}}
				{{--</table>--}}
					{{--</div>--}}
			{{--</div>--}}
		{{--</div>--}}
	{{--</div>--}}
{{--</div>--}}
@endsection
