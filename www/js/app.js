// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
var app = angular.module('rise',
    [
        'ionic',
        'ngCordova',
        'controllers',
        'services',
        'filters',
        'directives',
        'plangular',
        'tabSlideBox'
    ]
);

app.run(['$ionicPlatform', '$ionicScrollDelegate', '$rootScope', '$state', 'UserService', function($ionicPlatform, $ionicScrollDelegate, $rootScope, $state, UserService) {
    $ionicPlatform.ready(function() {
        // UserService.setUser({userID: 10155092792384782});

        if(objIsEmpty(UserService.getUser())){
          $state.go('artists');
        }else{
          $state.go('artists');
        }

        if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
          cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
          cordova.plugins.Keyboard.disableScroll(true);
        }

        if(window.StatusBar) {
            StatusBar.styleLightContent();
        }

        $rootScope.scrollTop = function() {
            $ionicScrollDelegate.scrollTop(true);
        };
    });
}]);

app.config(['$stateProvider', '$urlRouterProvider', '$ionicConfigProvider', '$sceDelegateProvider', '$sceProvider', function($stateProvider, $urlRouterProvider, $ionicConfigProvider, $sceDelegateProvider, $sceProvider){
    $ionicConfigProvider.scrolling.jsScrolling(false);
    $ionicConfigProvider.backButton.previousTitleText(false).text(' ');
    $ionicConfigProvider.navBar.alignTitle('center');
    $ionicConfigProvider.views.transition('none');
    $ionicConfigProvider.tabs.position('bottom');
    $sceDelegateProvider.resourceUrlWhitelist(['self', new RegExp('^(http[s]?):\/\/(w{3}.)?youtube\.com/.+$')]);
    $sceProvider.enabled(false);

    $stateProvider.state('welcome', {
        url: '/welcome',
        templateUrl: 'templates/welcome.html',
        controller: 'WelcomeCtrl'
    })

    .state('user',{
        url:'/user',
        templateUrl: 'templates/user.html',
        controller: 'UserCtrl'
    })
    .state('settings',{
        url:'/settings',
        templateUrl: 'templates/settings.html',
        controller: 'SettingsCtrl'
    })
    .state('help',{
        url:'/help',
        templateUrl: 'templates/help.html',
        controller: 'HelpCtrl'
    })
    .state('termsofuse',{
        url:'/termsofuse',
        templateUrl: 'templates/termsofuse.html',
        controller: 'TermsOfUseCtrl'
    })
    .state('about',{
        url:'/about',
        templateUrl: 'templates/about.html'
    })
    .state('artists',{
        url:'/artists',
        templateUrl: 'templates/artists.html',
        controller: 'ArtistsCtrl'
    })
    .state('artist',{
        url:'/artists/:artistId',
        templateUrl: 'templates/artist.html',
        controller: 'ArtistCtrl'
    })
    .state('search',{
        url:'/search',
        templateUrl: 'templates/search.html',
        controller: 'SearchCtrl'
    });

    $urlRouterProvider.otherwise('/welcome');

}]);

app.config(function(plangularConfigProvider){
    plangularConfigProvider.clientId = '5fd371b0c0d2c54f6de733c69c25105b';
});
