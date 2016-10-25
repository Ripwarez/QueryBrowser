<div class="qbr">
	<div
		id="{{ $id }}"
		data-id="{{ $id }}"
		data-page="{{ $currentPage }}"
		data-order-column="{{ $orderColumn }}"
		data-order-direction="{{ $orderDirection }}"
		data-form-action=""
		data-form-method="POST"
	>

		<div class="functions">
			@if ( !empty($createURI) )
				<a href="{{ $createURI }}" class="btn btn-primary">
					<span class="glyphicon glyphicon-plus-sign"></span> @lang('qbr.create')</a>
			@endif

			@if ( !empty($sortURI) )
				<a href="{{ $sortURI }}" class="btn btn-info">
					<span class="glyphicon glyphicon-resize-vertical"></span> @lang('qbr.sort')</a>
			@endif

			@if ( !empty($deleteURI) )
				<a href="javascript:void(0);" onclick="QBR.confirmDelete('{{ $id }}', 'Confirm delete items', 'Are you sure you want to delete selected items (<span class=\'nr-selected-rows\'></span>)?');" class="btn btn-danger btn-disabled" disabled>
					<span class="glyphicon glyphicon-remove-circle"></span> @lang('qbr.delete')
				</a>
			@endif

			<div class="pull-right">
				<input type="text" name="qbr_q" class="form-control table-search {{ $id }}_q" value="{{ $searchString }}" placeholder="@lang('qbr.search')">
			</div>
		</div>

		<table class="table table-striped">
			<!-- columns -->
			<thead>
				<tr>
					@if ( !empty($updateURI) )
						<th class="update"></th>
					@endif

					@foreach ( $columns as $columnKey => $columnValue )
						@if ( !$qbr->isHiddenColumn($columnKey) )
							@if ( $qbr->isStaticColumn($columnKey) )
								<th><span>{{ $columnValue }}</span></th>
							@else
								<th>
									<a href="javascript:void(0);" onclick="QBR.doSort('{{ $id }}','{{ $columnKey }}');" class="{{ ( $columnKey == $orderColumn ) ? 'active' : '' }}">
										{{ $columnValue }}
										@if ( $columnKey == $orderColumn )
											@if ( $orderDirection == 'desc' )
												<div class="pull-right sort_asc"></div>
											@else
												<div class="pull-right sort_desc"></div>
											@endif
										@else
											<div class="pull-right sort"></div>
										@endif
									</a>
								</th>
							@endif
						@endif
					@endforeach

					@if ( !empty($deleteURI) )
						<th class="delete">
							{{ Form::checkbox('', '', false, array('onclick' => sprintf("QBR.cbToggleAll('%s', this)", $id))) }}
						</th>
					@endif
				</tr>
			</thead>
			<tbody>
				<!-- records -->
				@foreach ( $results as $row )
					<tr>
						@if ( !empty($updateURI) )
							<td class="update">
								<a href="{{ $qbr->rewriteURIPlaceholders($updateURI, $row) }}">
									<span class="glyphicon glyphicon-pencil"></span>
								</a>
							</td>
						@endif

						@foreach ( $row as $column => $value )
							@if ( !$qbr->isHiddenColumn($column) )
								<td>
									@if ( is_array($value) || is_object($value) )
										<?php $value = print_r($value, TRUE); ?>
									@endif
									@if ( isset($firstColumn) && $column == $firstColumn )
										{{ HTML::link($qbr->rewriteURIPlaceholders($updateURI, $row), $value) }}
									@else
										{{ $qbr->highlightSearchString($value) }}
									@endif
								</td>
							@endif
						@endforeach

						@if ( !empty($deleteURI) )
							<td class="delete">
								<input type="checkbox" name="removeId[]" value="{{ $row[$deleteVar] }}" class="removeId" onclick="QBR.cbToggle('{{ $id }}', this);"/>
							</td>
						@endif
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@if ( $totalPages > 1 )
		<ul class="pagination">
			@if ( $firstPage !== FALSE )
				<li><a href="javascript:void(0);" onclick="QBR.gotoPage('{{ $id }}', {{ $firstPage }});" title="First">&laquo;&laquo;</a></li>
			@else
				<li class="disabled"><a href="javascript:void(0);">&laquo;&laquo;</a></li>
			@endif

			@if ( $previousPage !== FALSE )
				<li><a href="javascript:void(0);" onclick="QBR.gotoPage('{{ $id }}', {{ $previousPage }});">&laquo</a></li>
			@else
				<li class="disabled"><a href="javascript:void(0);">&laquo</a></li>
			@endif

			<?php $prev = -1; ?>
			@for ( $i = 1; $i <= $totalPages; $i++ )
				@if ( $totalPages > 0 )
					@if ( $i <= 3 || ($i >= $currentPage-2 && $i <= $currentPage+2) || $i > $totalPages-3 )
						@if ( $i > 1 && $i != $prev+1 )
							<li class="disabled"><a href="javascript:void(0);">...</a></li>
						@endif
						<li class="{{ ( $i == $currentPage ) ? 'active' : '' }}"><a href="javascript:void(0);" onclick="QBR.gotoPage('{{ $id }}', {{ $i }});">{{ $i }}</a></li>
						<?php $prev = $i; ?>
					@endif
				@else
					<li class="{{ ( $i == $currentPage ) ? 'active' : '' }}"><a href="javascript:void(0);" onclick="QBR.gotoPage('{{ $id }}', {{ $i }});">{{ $i }}</a></li>
				@endif
			@endfor

			@if ( $nextPage !== FALSE )
				<li><a href="javascript:void(0);" onclick="QBR.gotoPage('{{ $id }}', {{ $nextPage }});">&raquo;</a></li>
			@else
				<li class="disabled"><a href="javascript:void(0);">&raquo;</a></li>
			@endif

			@if ( $lastPage !== FALSE )
				<li><a href="javascript:void(0);" onclick="QBR.gotoPage('{{ $id }}', {{ $lastPage }});">&raquo;&raquo;</a></li>
			@else
				<li class="disabled"><a href="javascript:void(0);">&raquo;&raquo;</a></li>
			@endif
		</ul>
	@endif

	<div class="modal fade" id="{{ $id }}-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="QBR.doDelete('{{ $id }}', '{{ url($deleteURI) }}');"><span class="glyphicon glyphicon-ok"></span> Yes</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

</div> <!-- </qbr> -->