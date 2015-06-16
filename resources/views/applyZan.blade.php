@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>
				<div class="panel-body">
          <!-- apply form -->
          <form>
            <div class="form-group">
              <label>标题</label>
              <input type="text" class="form-control">
            </div>
            <div class="form-group">
              <label>个数</label>
              <select class="form-control">
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
              <textarea class="form-control"></textarea>
            </div>
          </form>
          <!-- ./ apply form -->
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
