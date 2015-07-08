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

      <!-- the one -->
			<div class="panel panel-primary">
        <div class="panel-heading"></div>
				<div class="panel-body">
          <div class="form-group">
            {{ $theOneProfile->name }}现在有{{ $theOneProfile->num }}个赞
          </div>
				</div>
			</div>
      <!-- ./ the one -->

      <!-- select -->
      <div class="form-group">
        <select class="form-control" id="select-channel" data-url="{{ $url }}">
          <option value="send-ok">发出申请 - 已通过</option>
          <option value="send-fail">发出申请 - 被拒绝</option>
          <option value="send-waiting">发出申请 - 等待中</option>
          <option value="receive-ok">收到申请 - 已通过</option>
          <option value="receive-fail">收到申请 - 已拒绝</option>
          <option value="receive-waiting">收到申请 - 等待中</option>
        </select>
      </div>
      <!-- ./ select -->

      <!-- panel -->
      @foreach($orders as $order)
			<div class="panel panel-primary">
        <div class="panel-heading"></div>
				<div class="panel-body">
          <div class="form-group">
            {{ $order->user_name }}{{ $comment[$channel] }}{{ $order->num }}个赞
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

<!-- js init -->
<script>
var config = {
  'channel':'{{ $channel }}'
};
</script>
<!-- ./ js init -->
@endsection
