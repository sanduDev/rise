var app = angular.module('services', []);

app.factory('UserService', function() {
    console.log('UserService');

    var setUser = function(user_data) {
        localStorage.starter_facebook_user = JSON.stringify(user_data);
    };

    var getUser = function(){
        return JSON.parse(localStorage.starter_facebook_user || '{}');
    };

    var clearData = function(user_data){
        localStorage.starter_facebook_user = JSON.stringify(user_data);
    };

    return {
        getUser: getUser,
        setUser: setUser,
        clearData: clearData
    };
});

app.factory('FavoritesArtists',['$http', '$q', 'UserService', function($http, $q, UserService){
  var deferred = $q.defer();
  var userId = UserService.getUser().userID;
  console.log('FavArtistsFactory '+userId);

  $http.get('http://rise-discover.com/api/favorites-artists.php?user_id='+userId)
      .success(function(data) {
          deferred.resolve(data);
      })
      .error(function(err) {
          deferred.reject(err);
      });

  return deferred.promise;
}]);

app.factory('FavoritesTracks',['$http', '$q', 'UserService', function($http, $q, UserService){
    var deferred = $q.defer();
    var userId = UserService.getUser().userID;
    console.log('FavTrackFactory '+userId);

    $http.get('http://rise-discover.com/api/favorites-tracks.php?user_id='+userId)
        .success(function(data) {
            deferred.resolve(data);
        })
        .error(function(err) {
            deferred.reject(err);
        });

    return deferred.promise;
}]);

app.factory('us_states_abb', function() {
    var usStates = [
        { name: 'ALABAMA', abbreviation: 'AL'},
        { name: 'ALASKA', abbreviation: 'AK'},
        { name: 'AMERICAN SAMOA', abbreviation: 'AS'},
        { name: 'ARIZONA', abbreviation: 'AZ'},
        { name: 'ARKANSAS', abbreviation: 'AR'},
        { name: 'CALIFORNIA', abbreviation: 'CA'},
        { name: 'COLORADO', abbreviation: 'CO'},
        { name: 'CONNECTICUT', abbreviation: 'CT'},
        { name: 'DELAWARE', abbreviation: 'DE'},
        { name: 'DISTRICT OF COLUMBIA', abbreviation: 'DC'},
        { name: 'FEDERATED STATES OF MICRONESIA', abbreviation: 'FM'},
        { name: 'FLORIDA', abbreviation: 'FL'},
        { name: 'GEORGIA', abbreviation: 'GA'},
        { name: 'GUAM', abbreviation: 'GU'},
        { name: 'HAWAII', abbreviation: 'HI'},
        { name: 'IDAHO', abbreviation: 'ID'},
        { name: 'ILLINOIS', abbreviation: 'IL'},
        { name: 'INDIANA', abbreviation: 'IN'},
        { name: 'IOWA', abbreviation: 'IA'},
        { name: 'KANSAS', abbreviation: 'KS'},
        { name: 'KENTUCKY', abbreviation: 'KY'},
        { name: 'LOUISIANA', abbreviation: 'LA'},
        { name: 'MAINE', abbreviation: 'ME'},
        { name: 'MARSHALL ISLANDS', abbreviation: 'MH'},
        { name: 'MARYLAND', abbreviation: 'MD'},
        { name: 'MASSACHUSETTS', abbreviation: 'MA'},
        { name: 'MICHIGAN', abbreviation: 'MI'},
        { name: 'MINNESOTA', abbreviation: 'MN'},
        { name: 'MISSISSIPPI', abbreviation: 'MS'},
        { name: 'MISSOURI', abbreviation: 'MO'},
        { name: 'MONTANA', abbreviation: 'MT'},
        { name: 'NEBRASKA', abbreviation: 'NE'},
        { name: 'NEVADA', abbreviation: 'NV'},
        { name: 'NEW HAMPSHIRE', abbreviation: 'NH'},
        { name: 'NEW JERSEY', abbreviation: 'NJ'},
        { name: 'NEW MEXICO', abbreviation: 'NM'},
        { name: 'NEW YORK', abbreviation: 'NY'},
        { name: 'NORTH CAROLINA', abbreviation: 'NC'},
        { name: 'NORTH DAKOTA', abbreviation: 'ND'},
        { name: 'NORTHERN MARIANA ISLANDS', abbreviation: 'MP'},
        { name: 'OHIO', abbreviation: 'OH'},
        { name: 'OKLAHOMA', abbreviation: 'OK'},
        { name: 'OREGON', abbreviation: 'OR'},
        { name: 'PALAU', abbreviation: 'PW'},
        { name: 'PENNSYLVANIA', abbreviation: 'PA'},
        { name: 'PUERTO RICO', abbreviation: 'PR'},
        { name: 'RHODE ISLAND', abbreviation: 'RI'},
        { name: 'SOUTH CAROLINA', abbreviation: 'SC'},
        { name: 'SOUTH DAKOTA', abbreviation: 'SD'},
        { name: 'TENNESSEE', abbreviation: 'TN'},
        { name: 'TEXAS', abbreviation: 'TX'},
        { name: 'UTAH', abbreviation: 'UT'},
        { name: 'VERMONT', abbreviation: 'VT'},
        { name: 'VIRGIN ISLANDS', abbreviation: 'VI'},
        { name: 'VIRGINIA', abbreviation: 'VA'},
        { name: 'WASHINGTON', abbreviation: 'WA'},
        { name: 'WEST VIRGINIA', abbreviation: 'WV'},
        { name: 'WISCONSIN', abbreviation: 'WI'},
        { name: 'WYOMING', abbreviation: 'WY' }
    ];

    return {
        all: function() {
            return usStates;
        }
    };
});

app.factory('Playlists', function(){

    var playlists = [
        {
            "id": 1,
            "name": "My fav rap",
            "tracks_id": []
        },{
            "id": 2,
            "name": "My fav weak rap",
            "tracks_id": []
        }
    ];

    return {
        all: function() {
            return playlists;
        },
        get: function(playlistId){
          for (var i = 0; i < playlists.length; i++) {
            if (playlists[i].id === parseInt(playlistId)) {
                return playlists[i];
            }
          }
            return null;
        },
        add: function(item) {
            playlists.push(item);
            console.log(playlists);
        },
        addtrack: function(playlistId, track_id){
            var playlistId = playlistId - 1;
            playlists[playlistId]['tracks_id'].push(track_id);
            console.log(playlists);
        }
    };
});
