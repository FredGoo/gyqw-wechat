@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-12">
      <!-- my -->
			<div class="panel panel-primary">
        <div class="panel-heading"></div>
				<div class="panel-body">
          <div class="form-group">
            {{ $profile->name }}现在有{{ $profile->num }}个赞
          </div>
				</div>
			</div>
      <!-- ./ my -->

      <!-- panel -->
      @foreach($orders as $order)
			<div class="panel panel-primary">
        <div class="panel-heading"></div>
				<div class="panel-body">
          <div class="form-group">
            宝宝得到{{ $order->num }}个赞
          </div>
          <div class="form-group">
            {{ $order->updated_at }}
          </div>
          <div class="form-group">
            {{ $order->content }}
          </div>
				</div>
			</div>
      @endforeach
      <!-- ./ panel -->
		</div>
	</div>
</div>
@endsection
