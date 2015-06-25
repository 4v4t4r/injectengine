var InjectEngine = InjectEngine || {};

InjectEngine = {
	_injectURL: null,

	init: function(injectURL) {
		console.log('InjectEngine-JS: Init');

		// Setup the inject URL
		this._injectURL = injectURL;

		// Bind all the flag submits
		$('.inject-flag:enabled').each(function() {
			// Going up 3 levels! Damn...
			$(this).parent().parent().parent().submit(function() {
				input = $(this).find('input');

				inject_id = input.data('inject-id');
				value = input.val();

				InjectEngine.handleFlagSubmit(inject_id, value);

				return false;
			});
		});

		// Bind to the hint modal
		$('#hintModal').on('show.bs.modal', function (event) {
			button = $(event.relatedTarget);
			modal = $(this);
			injectid = button.data('inject-id');

			// Get the Inject Modal content + inject it in!
			$.get(InjectEngine._injectURL+'/hint/'+injectid, function(data) {
				modal.find('.modal-body').html(data);

				// Rebind to the new HTML
				$('.hint-btn').click(function() {
					InjectEngine.handleHintBtn(injectid);
				});
			});
		});
	},

	handleFlagSubmit: function(id, value) {
		$
			.post(this._injectURL+'/submit', {id: id, value: value})
			.done(function() {
				// Reload the page
				window.location.reload();
			})
			.error(function() {
				$('#inject'+id+'-invalid').hide().removeClass('hidden').fadeIn(1000);
			});
	},

	handleHintBtn: function(injectid) {
		$
			.post(this._injectURL+'/takeHint', {id: injectid})
			.done(function() {
				// Reload the modal
				$.get(InjectEngine._injectURL+'/hint/'+injectid, function(data) {
					$('#hintModal').find('.modal-body').html(data);

					// Rebind to the new HTML
					$('.hint-btn').click(function() {
						InjectEngine.handleHintBtn(injectid);
					});
				});
			})
			.error(function() {
				alert('Request for hint failed. Please contact the White Team.');
			});
	},
};
