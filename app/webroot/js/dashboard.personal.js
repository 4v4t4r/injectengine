var InjectEngine_Dashboard_Personal = InjectEngine_Dashboard_Personal || {};

InjectEngine_Dashboard_Personal = {
	_url: null,
	_teams: null,

	_refreshRate: (30*1000), // 30 seconds

	init: function(url, teams) {
		console.log('InjectEngine_Dashboard_Personal-JS: Init');

		// Setup the URL + teams
		this._url = url;
		this._teams = teams;

		// Load the initial data
		this.loadTeamStatus();

		// Setup the intervals
		setInterval(InjectEngine_Dashboard_Personal.loadTeamStatus, this._refreshRate);
	},

	loadTeamStatus: function() {
		that = InjectEngine_Dashboard_Personal;
		url = that._url+'/getTeamsStatus/'+that._teams;

		$
			.get(url)
			.done(function(data) {
				$('#teamStatus-group').html(data);
			});
	},
};
