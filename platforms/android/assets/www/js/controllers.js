var app = angular.module('controllers', ['ionic']);

app.controller('WelcomeCtrl', function($scope, $rootScope, $state, $q, UserService, $ionicLoading, $http) {

    if(!objIsEmpty(UserService.getUser())){
      $state.go('artists');
    }

    // This is the success callback from the login method
    var fbLoginSuccess = function(response) {
        if (!response.authResponse){
          fbLoginError("Cannot find the authResponse");
          return;
        }

        var authResponse = response.authResponse;

        getFacebookProfileInfo(authResponse)
        .then(function(profileInfo) {
            // alert(profileInfo);
          // For the purpose of this example I will store user data on local storage
            UserService.setUser({
                authResponse: authResponse,
                userID: profileInfo.id,
                name: profileInfo.name,
                first_name: profileInfo.first_name,
                last_name: profileInfo.last_name,
                email: profileInfo.email,
                picture : "https://graph.facebook.com/" + authResponse.userID + "/picture?type=large&redirection=true"
            });

          //  alert(UserService.getUser().userID);
          //  alert(UserService.getUser().name);
          //  alert(UserService.getUser().email);

            var facebook_id = UserService.getUser().userID;
            var name = UserService.getUser().name;
            var email = UserService.getUser().email;
            var picture = UserService.getUser().picture;

            $http.get('http://rise-discover.com/api/login.php?facebook_id='+facebook_id+'&name='+name+'&email='+email+'&picture='+picture)
                .success(function(response){

                    console.log(response);

                    // alert(response);
                    // alert(response.data);

                    if(response == 'success' || response == 'user_exists'){
                        $ionicLoading.hide();

                        $scope.success = true;

                        $state.go('artists');
                    }else{
                        $scope.success = false;

                        $ionicLoading.show({
                            template: response + response.data + 'Sorry, impossible to login, please try again later',
                            duration: 4000
                        });

                        $state.go('welcome');
                    }

                })
                .error(function (response, data, status, header, config) {
                    $ionicLoading.show({
                        template: response.data + response + 'Sorry, impossible to login, please try again later.',
                        duration: 4000
                    });

                    $state.go('welcome');
                });
            $ionicLoading.hide();

            $scope.success = true;

            $state.go('artists');
        }, function(fail){
          // Fail get profile info
            console.log('profile info fail', fail);
        });
    };

    // This is the fail callback from the login method
    var fbLoginError = function(error){
        console.log('fbLoginError', error);
        $ionicLoading.hide();
    };

    // This method is to get the user profile info from the facebook api
    var getFacebookProfileInfo = function (authResponse) {
        var info = $q.defer();

        facebookConnectPlugin.api('/me?fields=email,name&access_token=' + authResponse.accessToken, null,
            function (response) {
                info.resolve(response);
            },
            function (response) {
                info.reject(response);
            });

            return info.promise;
    };
    $scope.facebookSignOut = function() {

        UserService.setUser({});
    };
    //This method is executed when the user press the "Login with facebook" button
    $scope.facebookSignIn = function() {

        facebookConnectPlugin.getLoginStatus(function(success){
            if(success.status === 'connected'){
                  // The user is logged in and has authenticated your app, and response.authResponse supplies
                  // the user's ID, a valid access token, a signed request, and the time the access token
                  // and signed request each expire
                  console.log('getLoginStatus', success.status);
                  // Check if we have our user saved
                  var user = UserService.getUser('facebook');

                  if(!user.userID){
                      getFacebookProfileInfo(success.authResponse)
                      .then(function(profileInfo) {
                        // For the purpose of this example I will store user data on local storage
                        UserService.setUser({
                            authResponse: success.authResponse,
                            userID: profileInfo.id,
                            name: profileInfo.name,
                            email: profileInfo.email,
                            picture : "http://graph.facebook.com/" + success.authResponse.userID + "/picture?type=large&redirection=true"
                        });

                        $state.go('artists');
                      }, function(fail){
                        // Fail get profile info
                        console.log('profile info fail', fail);

                      });
                  }else{
                      $state.go('artists');
                  }
            } else {
              // If (success.status === 'not_authorized') the user is logged in to Facebook,
              // but has not authenticated your app
              // Else the person is not logged into Facebook,
              // so we're not sure if they are logged into this app or not.

              console.log('getLoginStatus', success.status);

              $ionicLoading.show({
                template: 'Logging in...'
              });

              // Ask the permissions you need. You can learn more about
              // FB permissions here: https://developers.facebook.com/docs/facebook-login/permissions/v2.4
              facebookConnectPlugin.login(['email', 'public_profile'], fbLoginSuccess, fbLoginError);
            }
        });
    };
});

app.controller('UserCtrl', ['$scope', '$rootScope', 'UserService', '$ionicActionSheet', '$state', '$ionicLoading', '$http', 'FavoritesArtists', '$timeout', function($scope, $rootScope, UserService, $ionicActionSheet, $state, $ionicLoading, $http, FavoritesArtists, $timeout){
    $scope.$watch(function () {
        $scope.user = UserService.getUser();
        $scope.picture = $scope.user.picture;
    });

    $scope.listCanSwipe = true;
}]);

app.controller('SearchCtrl', ['$scope', '$http', '$timeout', function($scope, $http, $timeout) {
    $scope.search = function(surname){

        $scope.hide = false;

        if(surname.length >= 3){
          $scope.done = false;
            $http.get("http://rise-discover.com/api/search.php?search=" + surname).then(function(response) {
              // $timeout(function() {
                $scope.ready = true;
                if(response.status === 200){
                  $scope.data = response.data;
                  console.log(response.data, $scope.data.length);
                }
              // }, 1000);

            }).finally(function() {
              $scope.done = true;
            });
        }else{
          $scope.hide = true;
        }
    };
}]);

app.controller('FavArtistsAddCtrl', ['$scope', 'FavoritesArtists', 'UserService', '$http', '$state', '$ionicLoading', '$stateParams', function($scope, FavoritesArtists, UserService, $http, $state, $ionicLoading, $stateParams){

    var artistId = $stateParams.artistId;
    var userId = UserService.getUser().userID;

    var url_artist = 'http://rise-discover.com/api/artist.php?user_id='+artistId;

    // function to add artists to factory and db
    $scope.addFavoriteArtist = function(artistId){
        $scope.popup = true;
        FavoritesArtists.then(function(result) {
            $http
            .get('http://rise-discover.com/api/favorites-artists-add.php?user_id='+userId+'&artist_id='+artistId)
            .success(function(response) {
                $http.get(url_artist).then(function(response){
                    result.push(response.data[0]);
                    $scope.result = 'Artist added to favorites';

                    $ionicLoading.show({
                      template: 'Artist added to favorites',
                      duration: 1000
                    });

                });
                $scope.isFavorite = true;
            })
            .error(function (data, status, header, config) {
                $http.get(url_artist).then(function(response){
                    result.splice(response.data[0]);

                    $ionicLoading.show({
                      template: 'An error has occured, the artist have not been added to favorites',
                      duration: 1000
                    });
                });
                $scope.isFavorite = false;
            });
            console.log(result, FavoritesArtists);
        });
    };

    // function to remove artists from factory and db
    $scope.removeFavoriteArtist = function($index, artistId){
        console.log('removeFavoriteArtist', $index, artistId);

        FavoritesArtists.then(function(result) {
            $http
            .get('http://rise-discover.com/api/favorites-artists-delete.php?user_id='+userId+'&artist_id='+artistId)
            .success(function(response){
                $http.get('http://rise-discover.com/api/artist.php?user_id='+artistId).then(function(response){
                // if we remove an artist from the artist page, we need to find the object's index in the result array
                if($index == null){
                  console.log('noindex');
                    for(var i=0;i<result.length;i++){
                        if(JSON.stringify(result[i]) === JSON.stringify(response.data[0])){
                            result.splice(i,1);
                        }
                    }
                }else{
                    console.log('index ' + $index);
                    result.splice($index,1);
                }

                console.log('favorites after deletion ', result);

                $ionicLoading.show({
                  template: 'Artist removed from favorites',
                  duration: 1000
                });

                $scope.isFavorite = false;
                });
            })
            .error(function (data, status, header, config) {
                $ionicLoading.show({
                  template: 'An error has occured, the artist have not been removed to favorites',
                  duration: 1000
                });
                $scope.isFavorite = true;
            });
        });
    };

    FavoritesArtists.then(function(result){
        var favArr = [];

        for(var i=0;i<result.length;i++){
            favArr.push(result[i].user_id);
        }

        if(inArray(artistId, favArr)){
            $scope.isFavorite = true;
        }else{
            $scope.isFavorite = false;
        }

    });
}]);

app.controller('FavArtistsListCtrl', ['$scope','$q', 'UserService', 'FavoritesArtists', '$http', '$timeout', function($scope, $q, UserService, FavoritesArtists, $http, $timeout){
    $scope.loading = true;
    $scope.shouldShowDelete = false;
    $scope.listCanSwipe = true;
    $scope.result = false;

    $scope.refresh = function(){
      FavoritesArtists.then(function(result){
        console.log(result);
      });
    };

    $scope.alert = function(){
      alert('FavArtistsListCtrl ' + UserService.getUser().userID);
    };

    FavoritesArtists.then(function(result) {
        console.log(result);

        $scope.favArtists = result;
        $scope.loading = false;

        if(result > 0){
          $scope.result = true;
        }
    });

    $scope.doRefreshArtists = function(){
        FavoritesArtists.then(function(result) {
            $scope.favArtists = result;
        });
        $scope.$broadcast('scroll.refreshComplete');
    };
}]);

app.controller('FavTracksAddCtrl', ['$scope', 'FavoritesTracks', 'UserService', '$http', 'plangularConfig', '$ionicLoading', function($scope, FavoritesTracks, UserService, $http, plangularConfig, $ionicLoading){
    var userId = UserService.getUser().userID;
    //var userId = 10155092792384782;
    var url_tracks = 'http://rise-discover.com/api/favorites-tracks.php?user_id='+userId;

    // function to add artists to factory and db
    // artist.user_id, track.permalink_url, track.title, track.artwork_url
    $scope.addFavoriteTrack = function(artistId, surname, permalink_url, src, title, artwork_url,track){
      // console.log(artistId, surname, permalink_url, src, title, artwork_url);

      var favIcon = document.getElementById(track.id);

        

      FavoritesTracks.then(function(result){
        console.log(result);
        // debugger
         for(var i=0; i<result.length; i++){
          console.log(result[i].favorite_track_title);

          if(result[i].favorite_track_title==title){
            console.log('already Fav');
             $ionicLoading.show({
                  template: 'Already added to favorites',
                  duration: 1000
                });
            return;
          }
         }
        
        // return;
        $http
        .get('http://rise-discover.com/api/favorites-tracks-add.php?user_id='+userId+'&artist_id='+artistId+'&surname='+surname+'&permalink_url='+permalink_url+'&src='+src+'&title='+title+'&artwork='+artwork_url)
        .success(function(response){
            result.push({
              favorite_track_artist_id: artistId,
              favorite_track_artist_surname: surname,
              favorite_track_permalink_url: permalink_url,
              favorite_track_src: src,
              favorite_track_title: title,
              favorite_track_artwork: artwork_url
            });

            $ionicLoading.show({
              template: 'Track added to favorites',
              duration: 1000
            });
            favIcon.className += " Red";
        })
        .error(function (data, status, header, config) {
            $http.get(url_tracks).then(function(response){
                console.log(response);
                result.splice(response.data[0]);

                $ionicLoading.show({
                  template: 'An error has occured, the track have not been added to favorites',
                  duration: 1000
                });
            });
        });
        console.log(result, FavoritesTracks);
      });
    };

    $scope.removeFavoriteTrack = function($index, data){
        console.log('removeFavoriteArtist', $index, data);

        FavoritesTracks.then(function(result) {
          console.log(result);
            $http
            .get('http://rise-discover.com/api/favorites-tracks-delete.php?user_id='+userId+'&iadzalziudn='+data)
            .success(function(response){
                $http.get(url_tracks).then(function(response){

                console.log(result);

                result.splice($index,1);

                console.log('favorites after deletion ', result, response);

                $ionicLoading.show({
                  template: 'Track removed from favorites',
                  duration: 1000
                });

                $scope.isFavorite = false;
                });
            })
            .error(function (data, status, header, config) {

                $ionicLoading.show({
                  template: 'An error has occured, the track have not been removed from favorites',
                  duration: 1000
                });

                $scope.isFavorite = true;
            });
        });
      };

}]);

app.controller('FavTracksListCtrl', ['$scope','FavoritesTracks', function($scope, FavoritesTracks){
    $scope.loading = true;
    $scope.shouldShowDelete = false;
    $scope.listCanSwipe = true;
    $scope.result = false;

    FavoritesTracks.then(function(result) {
        console.log(result);

        $scope.favTracks = result;
        $scope.loading = false;

        if(result > 0){
          $scope.result = true;
        }
    });

    $scope.doRefreshTracks = function()
    {
        FavoritesTracks.then(function(result){
            $scope.favTracks = result;
            console.log(result);
            $scope.$broadcast('scroll.refreshComplete');
        });
    };
}]);

app.controller('ArtistsCtrl', ['$scope', '$cordovaGeolocation', '$ionicLoading', '$http', '$ionicPlatform', '$rootScope', 'UserService', '$timeout', function($scope, $cordovaGeolocation, $ionicLoading, $http, $ionicPlatform, $rootScope, UserService, $timeout) {

    var posOptions = {timeout: 20000, enableHighAccuracy: true, maximumAge: 36000};

    function gps(posOptions)
    {
        $scope.searching = true;
        
        $cordovaGeolocation.getCurrentPosition(posOptions).then(function(position) {

            $scope.searching = false;

//            var lat  = position.coords.latitude,
//               long = position.coords.longitude;

            var lat  = 40.821216,
                long = -73.942684;

               console.log(lat, long);

            json_url = ('https://maps.googleapis.com/maps/api/geocode/json?latlng='+ lat +','+ long + '&key=AIzaSyCRLns2pkmC1pqe6nQOl5NL6e4cI4sbwtQ');

            $http.get(json_url).then(function(response) {
                // console.log(response.data);

                $ionicLoading.hide();

                $scope.address = response.data;

                var address_components = $scope.address.results[0].address_components,
                    levels_arr = [];


                // United States
                for(var i = 0; i < address_components.length; i++){
                    if(address_components[i].types[0] == 'locality'){
                        levels_arr.push(address_components[i]);
                    }else if(address_components[i].types[0] == 'sublocality'){
                        levels_arr.push(address_components[i]);
                    }else if(address_components[i].types[0] == 'neighborhood'){
                        levels_arr.push(address_components[i]);
                    }else if(address_components[i].types[0] == 'political'){
                        levels_arr.push(address_components[i]);

                        console.log(address_components[i]);

                        var nyc = ['Bronx', 'Manhattan', 'Staten Island', 'Queens', 'Brooklyn'];

                        for(var i = 0; i < 5; i++){
                          if(inArray(address_components[i].long_name, nyc)){
                            levels_arr.push({long_name: "New York City", short_name: "New York City", types: ["locality"]});
                          }
                        }
                    }
                };

                console.log(levels_arr);

                var locations = valuesToArray(levels_arr);
                var levels = [];
                var positions = [];

                for(var i = 0; i < levels_arr.length; i++){
                    levels[i] = locations[i]['types'][0];
                    positions[i] = locations[i]['short_name'];

                    // console.log(positions[i]);

                    if(positions[i] == 'Upper Manhattan'){
                      positions[i] = 'Harlem';
                    }else{
                      positions[i] = locations[i]['short_name'];
                    }
                }

                $scope.tabs = [];
                $scope.artistslist = [];

                for(var i=0;i<positions.length;i++){
                    $scope.tabs.push({"position" : i, "text" : positions[i]});
                }

                if($scope.tabs.length > 0){
                    $scope.tablebox = true;
                }
                console.log('niveaux et valeurs',levels, positions);

                // we fill every levels with artists
                if(levels[0] && positions[0]){
                  $scope.hasMoreData0 = true;
                  $scope.artistslist0 = [];

                  var page = 2;

                  $scope.populateList0 = function() {
                    $scope.spin = true;

                    $http.get('http://rise-discover.com/api/risepagin.php?level='+levels[0]+'&value='+positions[0]+'&p='+page).then(function(response){
                        page++;

                        console.log(response);

                        $scope.spin = false;
                        if(response.data.length > 0){
                          angular.forEach(response.data, function(response){
                              $scope.artistslist0.push(response);
                          });
                        }else{
                          $scope.hasMoreData0 = false;
                        }
                    });
                    $scope.$broadcast('scroll.infiniteScrollComplete');
                  };

                  $http.get('http://rise-discover.com/api/rise.php?level='+levels[0]+'&value='+positions[0]).then(function(response){
                      $scope.artistslist0 = response.data;

                      console.log('0',response.data);
                  });
                }

                if(levels[1] && positions[1]){
                    $scope.artistslist1 = [];
                    $scope.hasMoreData1 = true;

                    var page = 2;

                    $scope.populateList1 = function() {
                      $scope.spin = true;

                      $http.get('http://rise-discover.com/api/risepagin.php?level='+levels[1]+'&value='+positions[1]+'&p='+page).then(function(response){
                          page++;

                          console.log(response);

                          $scope.spin = false;

                          if(response.data.length > 0){
                            angular.forEach(response.data, function(response){
                                $scope.artistslist1.push(response);
                            });
                          }else{
                            $scope.hasMoreData1 = false;
                          }
                      });
                      $scope.$broadcast('scroll.infiniteScrollComplete');
                    };

                    $http.get('http://rise-discover.com/api/rise.php?level='+levels[1]+'&value='+positions[1]).then(function(response){
                        $scope.artistslist1 = response.data;

                        console.log('1', response.data, 'http://rise-discover.com/api/rise.php?level='+levels[1]+'&value='+positions[1]);
                    });
                }

                if(levels[2] && positions[2]){
                    $scope.hasMoreData2 = true;
                    $scope.artistslist2 = [];

                    var page = 2;

                    $scope.populateList2 = function() {
                      $scope.spin = true;

                      $http.get('http://rise-discover.com/api/risepagin.php?level='+levels[2]+'&value='+positions[2]+'&p='+page).then(function(response){
                          page++;

                          $scope.spin = false;


                          if(response.data.length > 0){
                            angular.forEach(response.data, function(response){
                                $scope.artistslist2.push(response);
                            });
                          }else{
                            $scope.hasMoreData2 = false;
                          }
                      });
                      $scope.$broadcast('scroll.infiniteScrollComplete');
                    };

                    $http.get('http://rise-discover.com/api/rise.php?level='+levels[2]+'&value='+positions[2]).then(function(response){
                        $scope.artistslist2 = response.data;
                        console.log('2', response.data, 'http://rise-discover.com/api/rise.php?level='+levels[2]+'&value='+positions[2]);
                    });
                }

                // if(levels[3] && positions[3]){
                //     $scope.hasMoreData3 = true;
                //     $scope.artistslist3 = [];
                //
                //     var page = 2;
                //
                //     $scope.populateList3 = function() {
                //       $scope.spin = true;
                //
                //       $http.get('http://homechefhome.fr/rise/risepagin.php?level='+levels[3]+'&value='+positions[3]+'&p='+page).then(function(response){
                //           page++;
                //
                //           $scope.spin = false;
                //
                //           if(response.data.length > 0){
                //             angular.forEach(response.data, function(response){
                //                 $scope.artistslist3.push(response);
                //             });
                //           }else{
                //             $scope.hasMoreData3 = false;
                //           }
                //       });
                //       $scope.$broadcast('scroll.infiniteScrollComplete');
                //     };
                //
                //     $http.get('https://homechefhome.fr/rise/rise.php?level='+levels[3]+'&value='+positions[3]).then(function(response){
                //         $scope.artistslist3 = response.data;
                //         console.log('3', response.data, 'https://homechefhome.fr/rise/rise.php?level='+levels[3]+'&value='+positions[3]);
                //     });
                // }
                //
                // if(levels[4] && positions[4]){
                //     $scope.hasMoreData4 = true;
                //     $scope.artistslist4 = [];
                //
                //     var page = 2;
                //
                //     $scope.populateList4 = function() {
                //       $scope.spin = true;
                //
                //       $http.get('http://homechefhome.fr/rise/risepagin.php?level='+levels[4]+'&value='+positions[4]+'&p='+page).then(function(response){
                //           page++;
                //
                //           $scope.spin = false;
                //
                //           if(response.data.length > 0){
                //             angular.forEach(response.data, function(response){
                //                 $scope.artistslist4.push(response);
                //             });
                //           }else{
                //             $scope.hasMoreData4 = false;
                //           }
                //       });
                //       $scope.$broadcast('scroll.infiniteScrollComplete');
                //     };
                //
                //     $http.get('https://homechefhome.fr/rise/rise.php?level='+levels[4]+'&value='+positions[4]).then(function(response){
                //         $scope.artistslist4 = response.data;
                //         console.log('4', response.data, 'https://homechefhome.fr/rise/rise.php?level='+levels[4]+'&value='+positions[4]);
                //     });
                // }
                //
                // if(levels[5] && positions[5]){
                //     $scope.hasMoreData5 = true;
                //     $scope.artistslist5 = [];
                //
                //     var page = 2;
                //
                //     $scope.populateList5 = function() {
                //       $scope.spin = true;
                //
                //       $http.get('http://homechefhome.fr/rise/risepagin.php?level='+levels[5]+'&value='+positions[5]+'&p='+page).then(function(response){
                //           page++;
                //
                //           $scope.spin = false;
                //
                //           if(response.data.length > 0){
                //             angular.forEach(response.data, function(response){
                //                 $scope.artistslist5.push(response);
                //             });
                //           }else{
                //             $scope.hasMoreData5 = false;
                //           }
                //       });
                //       $scope.$broadcast('scroll.infiniteScrollComplete');
                //     };
                //
                //     $http.get('https://homechefhome.fr/rise/rise.php?level='+levels[5]+'&value='+positions[5]).then(function(response){
                //         $scope.artistslist5 = response.data;
                //         console.log('5', response.data, 'https://homechefhome.fr/rise/rise.php?level='+levels[5]+'&value='+positions[5]);
                //     });
                // }
                //
                // if(levels[6] && positions[6]){
                //     $scope.hasMoreData6 = true;
                //     $scope.artistslist6 = [];
                //
                //     var page = 2;
                //
                //     $scope.populateList6 = function() {
                //       $scope.spin = true;
                //
                //       $http.get('http://homechefhome.fr/rise/risepagin.php?level='+levels[6]+'&value='+positions[6]+'&p='+page).then(function(response){
                //           page++;
                //
                //           $scope.spin = false;
                //
                //           if(response.data.length > 0){
                //             angular.forEach(response.data, function(response){
                //                 $scope.artistslist6.push(response);
                //             });
                //           }else{
                //             $scope.hasMoreData6 = false;
                //           }
                //       });
                //       $scope.$broadcast('scroll.infiniteScrollComplete');
                //     };
                //
                //     $http.get('https://homechefhome.fr/rise/rise.php?level='+levels[6]+'&value='+positions[6]).then(function(response){
                //         $scope.artistslist6 = response.data;
                //         console.log('6', response.data, 'https://homechefhome.fr/rise/rise.php?level='+levels[6]+'&value='+positions[6]);
                //     });
                // }
            });
        }, function(err) {
            $scope.allowRefresh = true;
            console.log(err);
        });
    }

    $scope.updateMyPosition = function(){
        var onSuccess = function(position) {
             console.log('Latitude: '          + position.coords.latitude          + '\n' +
                   'Longitude: '         + position.coords.longitude         + '\n' +
                   'Altitude: '          + position.coords.altitude          + '\n' +
                   'Accuracy: '          + position.coords.accuracy          + '\n' +
                   'Altitude Accuracy: ' + position.coords.altitudeAccuracy  + '\n' +
                   'Heading: '           + position.coords.heading           + '\n' +
                   'Speed: '             + position.coords.speed             + '\n' +
                   'Timestamp: '         + position.timestamp                + '\n');
         };

         // onError Callback receives a PositionError object
         function onError(error) {
            console.log('code: '    + error.code    + '\n' + 'message: ' + error.message + '\n');
         }
        navigator.geolocation.getCurrentPosition(onSuccess, onError);

        if(gps(posOptions)){
            console.log('fire !!');
        }

        $scope.$broadcast('scroll.refreshComplete');
    };

    $scope.gps = gps(posOptions);

    var watchOptions = {timeout : 3000, enableHighAccuracy: false};
    var watch = $cordovaGeolocation.watchPosition(watchOptions);

    watch.then(
       null,

       function(err) {
          console.log(err);
       },

       function(position) {
          var lat  = position.coords.latitude,
              long = position.coords.longitude;
          console.log(lat + '' + long);
          // alert(lat + '' + long);
       }
    );

    watch.clearWatch();

}]);

app.controller('ArtistCtrl', ['$scope', '$stateParams', '$http','$sce', 'UserService','$ionicHistory',  '$ionicLoading', function($scope, $stateParams, $http, $sce, UserService, $ionicHistory, $ionicLoading) {

    var artistId = $stateParams.artistId;
    var userId = UserService.getUser().userID;

    $scope.goBack = function() {
        console.log('Going back');
        // console.log($ionicHistory);
       

         $ionicHistory.goBack();
    };

    $ionicLoading.show({
        template: 'Loading data'
    });

    // collect data from artist
    $scope.collectData = function (artistId){

        // check if user has upvoted or not
        $http.get('http://rise-discover.com/api/upvoted.php?user_id='+userId+'&artist_id='+artistId).then(function(response){
            if(response.data == 'upvoted'){
                $scope.upvoted = true;
            }else{
                $scope.upvoted = false;
            }
        });

        $http.get('http://rise-discover.com/api/artist.php?user_id='+artistId).then(function(response){
            
            $ionicLoading.hide();
            
            console.log(response.data[0]);
            $scope.artist = response.data[0];
            $scope.ranks = $scope.artist.rank;
            $scope.locations = $scope.artist.location;

            if($scope.artist.actual_points == null){
                $scope.artist.actual_points = 0;
            };


            var videoId = str_replace($scope.artist.video, 'https://www.youtube.com/watch?v=', '');

            $scope.thumbnail = $sce.trustAsResourceUrl("http://i4.ytimg.com/vi/"+videoId+"/mqdefault.jpg");

            $scope.openVideoURL = function() {
                try {
                    var videoId = str_replace($scope.artist.video, 'https://www.youtube.com/watch?v=', '');
                    var portfolioURL = $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+videoId+"?rel=0&amp;showinfo=0");
                    console.log(portfolioURL);
                    window.open(portfolioURL, '_blank', 'location=no');
                } catch (err) {
                    console.log(err);
                }
            };

            // get out the soundcloud username from url to parse the tracks
            var soundcloud_username = str_replace($scope.artist.soundcloud, 'https://soundcloud.com/', '');
            var soundcloud_tracks  = "http://api.soundcloud.com/users/"+ soundcloud_username +"/tracks.json?client_id=5fd371b0c0d2c54f6de733c69c25105b";

            $scope.soundcloud_username = $sce.trustAsHtml(soundcloud_username);

            // fetch soundcloud's tracks
            $http.get(soundcloud_tracks).then(function(response){
                if(response.data.length > 0){
                    $scope.hasTracks = true;
                    $scope.tracks = response.data;
                }else{
                    $scope.hasTracks = false;
                }

                if(response.status === 200){
                    $scope.ready = true;
                }else{
                    $scope.ready = false;
                }
            });

            // track's more, disable for now
            $scope.tracksmore = function(id){
                $scope.hello = true;

                //appear in the view
                $scope.track_id = id;

                $scope.childActive = function(){
                    $scope.hello = false;
                };

                $scope.addToPlaylist = function(playlistId){
                    var trackId = id;

                    Playlists.addtrack(playlistId, trackId);
                    alert('Song added');
                    $scope.playlistsModal = false;
                };

                $scope.showPlaylists = function(){
                    $scope.playlistsModal = true;
                };

                $scope.hidePlaylists = function(){
                    $scope.playlistsModal = false;
                };
            };

            // toggle bio/rank
            $scope.toggleAboutRank = function () {
                rank = true;
            };
        });

    };

    $scope.options = {
        loop: false,
        speed: 500
    };

    $scope.$on("$ionicSlides.sliderInitialized", function(event, data){
        // data.slider is the instance of Swiper
        $scope.slider = data.slider;
    });

    $scope.$on("$ionicSlides.slideChangeStart", function(event, data){
        console.log('Slide change is beginning');
    });

    $scope.$on("$ionicSlides.slideChangeEnd", function(event, data){
        // note: the indexes are 0-based
        $scope.activeIndex = data.activeIndex;
        $scope.previousIndex = data.previousIndex;
    });

    // refresh list
    $scope.doRefresh = function() {
        $http.get('http://rise-discover.com/api/artist.php?user_id='+artistId).then(function(response){
           $scope.artist = response.data[0];
        }).finally(function() {
           $scope.$broadcast('scroll.refreshComplete');
        });
    };

    $scope.collectData(artistId);
}]);

app.controller('PlaylistsCtrl', ['$scope', 'Playlists', function($scope, Playlists){

    $scope.playlists = Playlists.all();

    $scope.addPlaylist = function(item) {
        var playlistAmount = $scope.playlists.length + 1;

        playlistAdded = {
            "id": playlistAmount,
            "name": item,
            "tracks_id": []
        };

        Playlists.add(playlistAdded);
        alert('Playlist created');
    };

}]);

app.controller('PlaylistCtrl', ['$scope', 'Playlists','$stateParams', function($scope, Playlists, $stateParams){
  $scope.playlist = Playlists.get($stateParams.playlistId);
}]);

app.controller('SettingsCtrl', ['$scope', 'UserService', '$ionicActionSheet', '$state', '$ionicLoading', function($scope, UserService, $ionicActionSheet, $state, $ionicLoading){

  $scope.showLogOutMenu = function() {
      var hideSheet = $ionicActionSheet.show({
          destructiveText: 'Logout',
          titleText: 'Are you sure you want to logout ?',
          cancelText: 'Cancel',
          cancel: function() {
              $ionicLoading.hide();
          },
          buttonClicked: function(index) {
              return true;
          },
          destructiveButtonClicked: function(){
              $ionicLoading.show({
                template: 'Logging out...'
              });
              // Facebook logout
              facebookConnectPlugin.logout(function(){
                  $ionicLoading.hide();

                  // empty old data
                  UserService.clearData({});
                  UserService.setUser({});

                  localStorage.removeItem("starter_facebook_user");

                  $state.go('welcome');
              },
              function(fail){
                $ionicLoading.hide();
              });
          }
      });
  };
}]);

app.controller('UpVoteCtrl', ['$scope', '$http', 'UserService', '$ionicLoading', function($scope, $http, UserService, $ionicLoading){

  $scope.upVote = function(artistId) {

     var upVoteBtn = document.getElementById('upVoteBtn');     
         upVoteBtn.disabled = true;

      
    $scope.pushed = true;
            
    if(UserService.getUser().userID){
      var userId = UserService.getUser().userID.toString();
    }
    
    if($scope.pushed == true){
        $http.get('http://rise-discover.com/api/upvote.php?user_id='+userId+'&artist_id='+artistId)
            .success(function(response){
                
                $scope.pushed = false;
                
                if(response == 'upvoted_removed'){

                    $scope.upvoted = false;

                    $scope.artist.actual_points = parseInt($scope.artist.actual_points) - 1;

                    console.log(response);
                    upVoteBtn.disabled = false;

                }else if(response == 'upvote_successful'){

                    $scope.upvoted = true;

                    $scope.artist.actual_points = parseInt($scope.artist.actual_points) + 1;

                    console.log(response);
                    upVoteBtn.disabled = false;

                }else if(response == 'upvoted_failed_3_user_does_not_exist'){

                    $ionicLoading.show({
                        template: 'User does not exist, who are you ?',
                        duration: 5000
                    });
                    upVoteBtn.disabled = false;

                }else{

                    $ionicLoading.show({
                        template: 'We don\'t know what, but something wrong happened.',
                        duration: 5000
                    });
                    upVoteBtn.disabled = false;

                }
            })
            .error(function (data, status, header, config) {

                $ionicLoading.show({
                  template: 'An error has occured, the artist have not been upvoted. Please try again later.',
                  duration: 2500
                });

                $scope.isFavorite = true;
                upVoteBtn.disabled = false;
            });
    }else{
        $scope.pushed = true;
    }
  };

}]);

app.controller('HelpCtrl', ['$scope', function($scope){
    $scope.groups = [
        { question: 'How does it work ?', answer: 'Rise is a platform that helps you discover the most popular hiphop artists according to your location. <br>Walk through NYC and discover the hottest local rappers.'},
        { question: 'What is the profile ?', answer: 'We take your location and give you a realtime ranking of the hottest hiphop artists  from your area. You can change the size of the area from the neighborhood you’re located in, to the whole city.'},
        { question: 'How the rankings are made ?', answer: 'Once you’re on a rapper’s profile, you can watch one of his YouTube video clip and listen to his Soundcloud tracks. You also have access to the different positions of the artist in the rankings. <br>If you enjoyed the music, hit the upvote button to support the artist and offer him more visibility !'},
        { question: 'What’s the point of the upvote ?', answer: 'Rise’s algorithm takes into account the statistics of each artist on his social medias, and the support from his fans on Rise. The more engagement he has on his social medias and the more upvote he gets on Rise, the higher he will be in the rankings. '},
        { question: 'Those rankings are bullshit !', answer: 'The upvote button rises the artist in the rankings. The higher the artist is, the more chances he has to get noticed on a larger area. '},
        { question: 'They are a lot of missing artists !', answer: 'We do our best to have the most complete database of artists. Nonetheless, we can’t be aware of every  single rapper in NYC without a little help.  If you know a great artist that is missing, please fill the following form and we’ll take a look at him.'},
        { question: 'I’m not from NYC : What should I do ?', answer: 'Unfortunately the full Rise experience is only available (for the moment) in NYC. However you can still search for your favorite  NYC rappers through the search engine and upvote them. Let us know if you woul’d like the app to be available in your city !'},
        { question: 'I’m a rapper ! How can I be featured ?', answer: 'If you’re a rapper please submit your application on the website. We’ll get back to you when your profile is available.'}
    ];

    $scope.toggleGroup = function(group) {
        if ($scope.isGroupShown(group)) {
            $scope.shownGroup = null;
        } else {
            $scope.shownGroup = group;
        }
    };
    
    $scope.isGroupShown = function(group) {
        return $scope.shownGroup === group;
    };
    
//    https://github.com/katzer/cordova-plugin-email-composer
    
    $scope.sendFeedback = function() {
         if(window.plugins && window.plugins.emailComposer) {
             window.plugins.emailComposer.showEmailComposerWithCallback(function(result) {
                 console.log("Response -> " + result);
             }, 
             "Feedback for your App", // Subject
             "",                      // Body
             ["test@example.com"],    // To
             null,                    // CC
             null,                    // BCC
             false,                   // isHTML
             null,                    // Attachments
             null);                   // Attachment Data
         }
     };
}]);

app.controller('TermsOfUseCtrl', ['$scope', function($scope){
    $scope.terms = [
        { terms: ''}
    ];
}]);