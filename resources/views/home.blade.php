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
				<div class="col-lg-12">
					<div class="nameplate">
						<a href="/site/{{ $site->id }}">{{$site->name}}</a>
					</div>
					@php( $clients = 0 )
					@foreach ($site->equipment as $equipment)
						@php( $clients += $equipment->libre_wireless('clients')->sensor_current )
					@endforeach

					<div class="site-connections"><strong>Clients:</strong> {{ $clients }}</div>

					<div class="site-description">{{$site->description}}</div>
				</div>

				@foreach ($site->equipment as $equipment)
					<div class="text-center equipment-block" style=" border-color: {{ $equipment->getHealthColor() }}">

						<a href="{{ url( "/equipment/" . $equipment->id ) }}" style="font-size: 13px;">
							@if( count($equipment->clients) >= 1 )
							<div class="tl-corner-badge" style=" border-color: {{ $equipment->getHealthColor() }}">
								{{count($equipment->clients)}}
							</div>
							@endif
							{{--<div class="text-center" style="width: 126px; display:inline-block; background-color: {{ $equipment->getHealthColor() }}">--}}

							{{--{{ $equipment->getHealthStatus() }}</div>--}}
							<img src="{{$equipment->icon() }}" style="width:64px; height: 64px;"><br>
								{!! str_replace('.IF.HAMWAN.CA', '', strtoupper( $equipment->hostname)) !!}</a><br>

					</div>
				@endforeach


				<div class=" map-block hidden-md  hidden-sm  hidden-xs text-right">
					<a href="{{ env('LIBRENMS_URL') }}/plugins/Weathermap/output/site-{{ strtolower($site->sitecode) }}.html">
						<img style="    height: 144px;    margin-top: -10px;    margin-bottom: -20px;" src="{{ env('LIBRENMS_URL') }}/plugins/Weathermap/output/site-{{ strtolower($site->sitecode) }}.png">
					</a>
				</div>
					<div style="clear: both"></div>

				</div>

		@endforeach
	</div>


@endsection
