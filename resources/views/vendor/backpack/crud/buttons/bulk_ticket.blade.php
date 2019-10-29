@if ($crud->hasAccess('bulkTicket') && $crud->get('list.bulkActions'))
	<a href="javascript:void(0)" onclick="bulkTicketEntries(this)" class="btn btn-sm btn-secondary bulk-button"><i class="fa fa-envelope"></i> Send Ticket</a>
@endif

@push('after_scripts')
<script>
	if (typeof bulkTicketEntries != 'function') {
	  function bulkTicketEntries(button) {

	      if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0)
	      {
	      	new Noty({
	          type: "warning",
	          text: "<strong>{{ trans('backpack::crud.bulk_no_entries_selected_title') }}</strong><br>{{ trans('backpack::crud.bulk_no_entries_selected_message') }}"
	        }).show();

	      	return;
	      }

	      var message = ("{{ trans('crud.bulk_ticket_are_you_sure') }}").replace(":number", crud.checkedItems.length);
	      var button = $(this);

	      // show confirm message
	      swal({
			  title: "{{ trans('backpack::base.warning') }}",
			  text: message,
			  icon: "warning",
			  buttons: {
			  	cancel: {
				  text: "{{ trans('backpack::crud.cancel') }}",
				  value: null,
				  visible: true,
				  className: "bg-secondary",
				  closeModal: true,
				},
			  	delete: {
				  text: "Send Tickets",
				  value: true,
				  visible: true,
				  className: "bg-success",
				}
			  },
			}).then((value) => {
				if (value) {
					var ajax_calls = [];
					var delete_route = "{{ url($crud->route) }}/bulk-ticket";

					// submit an AJAX delete call
					$.ajax({
						url: delete_route,
						type: 'POST',
						data: { entries: crud.checkedItems },
						success: function(result) {
						    // Show an alert with the result
							new Noty({
								type: "success",
								text: "<strong>{{ trans('crud.bulk_ticket_success_title') }}</strong><br>"+crud.checkedItems.length+"{{ trans('crud.bulk_ticket_success_message') }}"
							}).show();

						  	crud.checkedItems = [];
							  	crud.table.ajax.reload();
						},
						error: function(result) {
							// Show an alert with the result
							new Noty({
								type: "warning",
								text: "<strong>{{ trans('crud.bulk_ticket_error_title') }}</strong><br>{{ trans('crud.bulk_ticket_error_message') }}"
							}).show();
						}
					});
				}
			});
      }
	}
</script>
@endpush
