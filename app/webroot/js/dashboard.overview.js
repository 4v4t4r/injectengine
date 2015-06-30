var InjectEngine_Dashboard_Overview = InjectEngine_Dashboard_Overview || {};

InjectEngine_Dashboard_Overview = {
	_url: null,

	_refreshRate: (30*1000), // 30 seconds

	init: function(url) {
		console.log('InjectEngine_Dashboard_Overview-JS: Init');

		// Setup the URL + teams
		this._url = url;

		// Load the initial data
		this.loadTeamStatus();

		// Setup the intervals
		setInterval(InjectEngine_Dashboard_Overview.loadTeamStatus, this._refreshRate);
	},

	loadTeamStatus: function() {
		that = InjectEngine_Dashboard_Overview;
		url = that._url+'/getTeamsStatus';

		$
			.get(url)
			.done(function(data) {
				$('#teamStatus-group').html(data);
			});
	},
};
