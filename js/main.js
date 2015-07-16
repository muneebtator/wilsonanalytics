app = angular.module("app", ['ngRoute', 'ui.router', 'ngAnimate']);

angular.isempty = function(val){
	if(val === null || angular.isUndefined(val) == true || val == " " || val == ""){
		return true;
	}else{
		return false;
	}
}


app.config(function($stateProvider, $urlRouterProvider){

	$stateProvider
		.state("welcome", {
			url: '/welcome',
			templateUrl: "pages/welcome.html"
			}
		)
		.state("dash", {
			url: "/dash",
			templateUrl: "pages/dash.html",
			controller: "dashCtrl"
		})
		.state("start", {
			url: "/start",
			parent: "dash",
			templateUrl: "pages/start.php",
			controller: "startCtrl"
		})
		.state("addwebsite", {
			url: '/addwebsite',
			parent: "dash",
			templateUrl: "pages/addwebsite.html",
		})
		.state("website", {
			url: '/website/:id/:title/:url',
			parent: "dash",
			templateUrl: "pages/website.html",
			controler: "websiteCtrl"
		})
		.state("websitemore", {
			url: '/more',
			parent: "website",
			template: "lol"
		})
		.state("settings", {
			url: '/settings',
			parent: "dash",
			templateUrl: "pages/settings.html",
		})
		.state("myapps", {
			url: '/myapps',
			parent: "dash",
			templateUrl: "apps/myapps.php"
		})
		.state("appframe", {
			url: '/myapps/app/:filename',
			parent: "dash",
			templateUrl: "apps/appsframe.php",
		})
		.state("loginproblem", {
			url: '/loginproblem',
			templateUrl: "pages/loginproblem.html",
			controller: "logprob"
		})
});

app.service('user', function(){
	this.loginLocal = function(username, password){
		localStorage.setItem("username" , username);
		localStorage.setItem("password", password);
	}

	this.checkLogin = function(){
		if(localStorage.getItem("username") != null && localStorage.getItem("password") != null){
			return true;
		}else{
			return false;
		}
	}

});

app.directive('welcome', [function () {
	return {
		templateUrl: "pages/welcome.html"
	};
}])

app.directive('loading', [function () {
	return {
		scope: {
			show: "="
		},
		template: "<center ng-if='show'><h4>Loading...</h4></center>"
	};
}]);

app.service('dbrequest', function($http){
	this.doDbRequest = function(urll, meth, dataa){ 
		var request = $http({method: meth, url: urll, data: dataa});	
		return(request.then());
	}
});


app.directive('name', [function () {
	return {
		templateUrl: "pages/index/info.html"
	};
}])

app.directive('error', [function () {
	return {
		scope: {
			errorr: "=",
			errormessage: "="
		},
		template: "<div class='alert alert-danger toggle' style='margin-top: 10px;' ng-if='errorr'>{{errormessage}}</div>",
		controller: function($scope){
		}
	};
}]);


app.directive('success', [function () {
	return {
		scope: {
			successs: "=",
			successmessage: "="
		},
		template: "<div class='alert alert-success toggle' style='margin-top: 10px;' ng-if='successs'>{{successmessage}}</div>",
		controller: function($scope){
		}
	};
}]);


app.controller('welcomeCtrl', function ($scope, $location, user, dbrequest, $state, $rootScope) {


			dbrequest.doDbRequest("php/scripts/firstrun.php", "POST", null).then(function(response){
			firsttime = false;
			if(response.data.first == true){
				firsttime = true;
			}
		}).then(function(){
			if(firsttime == true){
				dbrequest.doDbRequest("php/scripts/updatesecret.php", "POST").then(function(){})
				loc = window.location.origin + window.location.pathname;
				dbrequest.doDbRequest("php/scripts/updateloc.php", "POST", {loc: loc}).then(function(){})
			}
		}).then(function(){
			if(firsttime == true){
				dbrequest.doDbRequest("php/scripts/changefirstrun.php", "POST", null).then(function(){})
			}
		});
	$scope.showloading = false;
	$scope.showlogin = true;

	if(user.checkLogin() == true){
			$scope.showloading = true;
			$scope.showlogin = false;
			$state.go("start");	
	}
	$scope.login = function(){
			if(angular.isempty($scope.username) || angular.isempty($scope.password)){
			$scope.error = true;
			$scope.message = "Please fill in all the fields!";
			return false;
		}

		dbrequest.doDbRequest("php/scripts/checklogin.php", "POST", {username: $scope.username, password: $scope.password, type:"login"}).then(function(response){
			if(response.data.status == false){
				$scope.error = true;
				$scope.message = "Please provide the right credentials.";

				}else{
				$scope.showloading = true;
				$scope.showlogin = false;

				$scope.password = response.data.pass;
				user.loginLocal($scope.username, $scope.password);
				$location.path("/dash");
			}
		});


	}



});

app.controller('logprob', function ($scope, $location, dbrequest) {

	$scope.logout = function(){
		localStorage.removeItem("username");
		localStorage.removeItem("password");
			dbrequest.doDbRequest("php/scripts/logout.php", "POST", null).then(function(response){});
				$location.path("/welcome");
	}
});

app.controller('dashCtrl', function ($scope, $state, dbrequest, user, $location) {
	$scope.logout = function(){
		localStorage.removeItem("username");
		localStorage.removeItem("password");
			dbrequest.doDbRequest("php/scripts/logout.php", "POST", null).then(function(response){});
				$location.path("/welcome");
			}

	if(user.checkLogin() == true){
		dbrequest.doDbRequest("php/scripts/checklogin.php", "POST", {username: localStorage.getItem("username"), password: localStorage.getItem("password"), type: "dash"}).then(function(response){
			if(response.data.status == false){
						localStorage.removeItem("username");
		localStorage.removeItem("password");
			dbrequest.doDbRequest("php/scripts/logout.php", "POST", null).then(function(response){

				$location.path("/loginproblem");
			});
			}
		});
	}else{
	 $state.go("welcome");
	}

	$scope.nowebsite = false;
	$scope.websites = {};
	$scope.success = false;
		dbrequest.doDbRequest("php/scripts/websites.php", "POST", null).then(function(response){
			if(response.data.haswebsites == false){
				$scope.nowebsite = true;
			}else{
				$scope.nowebsite = false;
				$scope.websites = response.data;
			}
		});


		$scope.addwebsite = function(title, url){
			$scope.title = title, $scope.url = url;

			if(angular.isempty($scope.title) || angular.isempty($scope.url)){
				$scope.error = true;
				$scope.message = "Please fill in all the fields!";
				return false;
			}

			dbrequest.doDbRequest("php/scripts/addwebsite.php", "POST", {title: $scope.title, website: $scope.url}).then(function(response){
				$scope.fetchnewwbesites = true;
				if(response.data.stat == false){
					$scope.error = true;
					$scope.message = "We had a problem, Please try again.";
					$scope.fetchnewwbesites = false;
				}
			}).then(function(){
				dbrequest.doDbRequest("php/scripts/websites.php", "POST", null).then(function(response){
					$scope.nowebsite = false;
					$scope.websites = response.data;
				});
			}).then(function(){
				$scope.success = true;
				$scope.smessage = "Website has been added!.";
			});

		}


});

app.controller('startCtrl', function($scope, $location, dbrequest, $state, $rootScope) {
		$scope.oldversion = false;
	dbrequest.doDbRequest("http://wilson.22web.org/updater.php", "GET", null).then(function(response){

		if(response.data[0].currentversion > $rootScope.verr){
			$scope.oldversion = true;
			$scope.newverlink = response.data[0].newversionlink;
			$scope.newverfeatures = response.data[0].demo;
		}
	});

	$scope.loadApp = function(apploc){
		document.cookie="apploc=" + apploc + "; expires=Thu, 18 Dec 2018 12:00:00 GMT; path=/";
		$state.go("appframe", {filename: apploc});
	}

	$scope.noapps = false;
	dbrequest.doDbRequest("php/scripts/status.php", "GET", null).then(function(response){
		$scope.status = response.data;
	});

	dbrequest.doDbRequest("php/scripts/getbookmarkapps.php", "POST", null).then(function(response){
		if(response.data.noapps == true){
			$scope.noapps = true;
		}else{
			$scope.apps = response.data;
		}
	});


	$scope.secrett = function(){
		if(angular.isempty($scope.secret)){
			alert("Provide a secret.");
			return false;
		}
	dbrequest.doDbRequest("php/scripts/checksecret.php", "POST", {secret: $scope.secret}).then(function(response){
		if(response.data.wrong == true){
			alert("You have provided the wrong secret.");
		}else if(response.data.wrong == false){
			var url = "php/scripts/secretcookie.php?secret=" + $scope.secret;
			location.href = url;
		}
	});


	}
});

app.controller('mainCtrl', function ($scope, $rootScope) {
	$scope.ver = 1.0;
	$rootScope.verr = 1.0;
});

app.controller('websiteCtrl', function ($scope, $stateParams, dbrequest) {
	$scope.title = $stateParams.title;
	$scope.websiteId = $stateParams.id;
	$scope.websiteUrl = $stateParams.url;
	$scope.returningvisits = 0;
	$scope.newvisits = 0;
	$scope.code = '<div id="wilson" website="1"></div>';

	/*pagesbar = [];
	refbar = [];
	devicepie = [];
	$scope.chartTypee = 'bar';
	$scope.chartTypeee = 'pie';
	$scope.datapiee = { series: ['Hits'], data : devicepie}
	$scope.configpiee = { labels: false, title : "Number of hits by devices", legend : { display: true, position:'right' }, innerRadius: 0, lineLegend: 'lineEnd',}
	$scope.datapie = { series: ['Hits'], data : refbar}
	$scope.configpie = { labels: false, title : "Top 40 referrals organized by numbers of refs", legend : { display: true, position:'right' }, innerRadius: 0, lineLegend: 'lineEnd',}
	$scope.data = { series: ['Hits'], data : pagesbar}
	$scope.chartType = 'bar';
	$scope.config = { labels: false, title : "Popular 40 pages organized by numbers of hits", legend : { display: true, position:'right' }, innerRadius: 0, lineLegend: 'lineEnd',}*/

	$scope.showFullUrl = function(url){
		alert(url);
	}
	dbrequest.doDbRequest("php/scripts/getrootloc.php", "POST", null).then(function(response){
		$scope.rootloc = response.data.loc;
	});

	dbrequest.doDbRequest("php/scripts/quickanalytics.php", "POST", {website: $stateParams.id}).then(function(response){
		$scope.totalhits = response.data.totalhits;
		$scope.todayhits = response.data.todayhits;
		$scope.lasthiton = response.data.lasthiton;
		$scope.topref = response.data.topref;
		$scope.linkbacks = response.data.linkbacks;
		$scope.yesterdayhits = response.data.yesterdayhits;
		$scope.msg1 = response.data.msg1;
		$scope.returningvisits = response.data.returningvisits;
		$scope.newvisits = response.data.newvisits;
	});

	dbrequest.doDbRequest("php/scripts/getpoppages.php", "POST", {website: $stateParams.id, type: "normal"}).then(function(response){
		$scope.pages = response.data;
	})
/*	dbrequest.doDbRequest("php/scripts/getpoppages.php", "POST", {website: $stateParams.id, type: null}).then(function(response){
		$scope.pagetemp = response.data;
		for (var i = 0; i < $scope.pagetemp.length; i++) {
			pagesbar[i] = {x: $scope.pagetemp[i].page, y: [$scope.pagetemp[i].hits]};
		}
	});*/
	dbrequest.doDbRequest("php/scripts/gettopref.php", "POST", {website: $stateParams.id, type: "normal"}).then(function(response){
		$scope.refs = response.data;
	});

/*	dbrequest.doDbRequest("php/scripts/gettopref.php", "POST", {website: $stateParams.id, type: null}).then(function(response){
		$scope.tempref = response.data;
		for (var i = 0; i < $scope.tempref.length; i++) {
			refbar[i] = {x: $scope.tempref[i].refwebsite, y: [$scope.tempref[i].totalrefs]};
		}
	});*/

	dbrequest.doDbRequest("php/scripts/getdeviceshare.php", "POST", {website: $stateParams.id}).then(function(response){
		$scope.deviceshare = response.data;
	});

	dbrequest.doDbRequest("php/scripts/getvisits.php", "POST", {website: $stateParams.id, type: "normal"}).then(function(response){
		$scope.visits = response.data;
	});

	dbrequest.doDbRequest("php/scripts/searchengines.php", "POST", {website: $stateParams.id, type: "normal"}).then(function(response){
		$scope.searchengines = response.data;
	});
	
	/*$scope.loadPageTraffic = function(){
		if(angular.isempty($scope.pageurl)){
			$scope.error = true;
			$scope.message = "Please fill in all the fields!";
			return false;
		}

	dbrequest.doDbRequest("php/scripts/getpagetraffic.php", "POST", {url: $scope.pageurl}).then(function(response){

	});*/


});

app.controller('settingsCtrl', function ($scope, dbrequest, user) {

	dbrequest.doDbRequest("php/scripts/getrootloc.php", "POST", null).then(function(response){
		$scope.rootloc = response.data.loc;
	});
	$scope.bowfish = false;
	$scope.success1 = false;
	$scope.success3 = false;

	dbrequest.doDbRequest("php/scripts/checkbowfish.php", "GET", null).then(function(response){
		if(response.data.has == true){
			$scope.bowfish = true;
		}
	});

	$scope.changecombo = function(){
		if(angular.isempty($scope.username) || angular.isempty($scope.password)){
			$scope.error = true;
			$scope.message = "Please fill in all the fields!";
			return false;
		}

		dbrequest.doDbRequest("php/scripts/changedetails.php", "POST", {olduser: localStorage.getItem('username'), oldpas: localStorage.getItem('password'), username: $scope.username, password: $scope.password}).then(function(response){
			if(response.data.stat == true){
				$scope.success1 = true;
				$scope.smessage1 = "Your Login details have been changed";
				user.loginLocal($scope.username, response.data.hashh);
			}else{
				$scope.error = true;
				$scope.message = "There was a problem changing account details, Please contact support.";
			}
		});
	}



	$scope.changeloc = function(){
		if(angular.isempty($scope.newdir)){
			$scope.error = true;
			$scope.message = "Please fill in all the fields!";
			return false;
		}

		dbrequest.doDbRequest("php/scripts/changerootloc.php", "POST", {username: localStorage.getItem('username'), password: localStorage.getItem('password'), loc: $scope.newdir}).then(function(response){
			if(response.data.stat == true){
				$scope.success3 = true;
				$scope.smessage3 = "The location has been successfully changed."
			}else{
				$scope.error = true;
				$scope.message = "There was a error, Please contact support.";
			}
		});
	}
});

app.directive('info', function(dbrequest){
	return {
		templateUrl: "pages/index/info.html",
	};
});

app.controller('infoCtrl', function ($scope, dbrequest) {
	$scope.first = false;
	dbrequest.doDbRequest("php/scripts/firstrun.php", "POST", null).then(function(response){
		if(response.data.first == true){
				$scope.first = true;
		}
	});	
});

app.controller('myappsCtrl', function ($scope, dbrequest, $state) {
	$scope.appname = "Muneeb App";
	$scope.hide = true;
	$scope.loadApp = function(apploc){
		document.cookie="apploc=" + apploc + "; expires=Thu, 18 Dec 2018 12:00:00 GMT; path=/";
		$state.go("appframe", {filename: apploc});
	}

	$scope.bookmarkApp = function(applocation, appname, appicon){
		dbrequest.doDbRequest("php/scripts/bookmarkapp.php", "POST", {appname: appname, applocation: applocation, appicon: appicon}).then(function(response){
			if(response.data.isthere == true){
				alert(appname + " is already bookmarked.");
			}else if(response.data.stat == true){
				alert(appname + " has been bookmarked.");
			}else{
				alert("A problem occoured bookmarking " + appname);
			}
		});

	}

	$scope.unbookmarkapp = function(applocation){
		dbrequest.doDbRequest("php/scripts/unbookmark.php", "POST", {applocation: applocation}).then(function(response){
			if(response.data.stat == true){
				alert("App has been removed from the app drawer.");
			}else{
				alert("There was a problem contacting servers. Try again.");
			}
		});
	}

	$scope.secrett = function(){
		if(angular.isempty($scope.secret)){
			alert("Provide the secret.");
			return false;
		}	
		dbrequest.doDbRequest("php/scripts/secretcookie.php", "POST", {secret: $scope.secret}).then(function(response){
			if(response.data.wrong == true){
				alert("Enter the correct secret");
			}else if(response.data.wrong == false){
				$scope.hide = false;
			}else{
				alert("There was a problem please try again.");
			}
		});
	}
});

app.controller('appframeCtrl', function ($scope, $stateParams) {
	$scope.filename = $stateParams.filename;
});

