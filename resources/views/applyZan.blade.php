@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading"></div>
				<div class="panel-body">
          <!-- apply form -->
          <form action="/submit-apply-zan" method="post">
            <div class="form-group">
              <label>标题</label>
              <input type="text" class="form-control" name="title">
            </div>
            <div class="form-group">
              <label>个数</label>
              <select class="form-control" name="num">
                <option value="1">1</option>
                <option value="1">2</option>
                <option value="1">3</option>
                <option value="1">5</option>
                <option value="1">10</option>
                <option value="1">15</option>
                <option value="1">30</option>
              </select>
            </div>
            <div class="form-group">
              <label>内容</label>
              <textarea class="form-control" name="content"></textarea>
            </div>
            <div class="form-group clearfix">
              <div class="pull-right">
                <button type="submit" class="btn btn-primary btn-lg">申请</button>
              </div>
            </div>
          </form>
          <!-- ./ apply form -->
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
