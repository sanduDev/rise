var app = angular.module('filters', []);

app.filter('positionSuffix', ['$filter', function ($filter) {

        function ordinal(value){
            return '<span class=\"ordinal\">' + value + '</span>';
        }

    return function (input) {
        if(input == '1'){
            return input + ordinal('st');
        }else if(input == '2'){
            return input + ordinal('nd');
        }else if(input == '3'){
            return input + ordinal('rd');
        }else{
            return input + ordinal('th');
        }
    };
}]);

app.filter('trustAsHtml', function($sce) {
    return function(val) {
        return $sce.trustAsHtml(val);
    };
});

app.filter('trustAsResourceUrl', ['$sce', function($sce) {
    return function(val) {
        return $sce.trustAsResourceUrl(val);
    };
}]);

app.filter('str_replace', function () {
    return function (value) {
        return (!value) ? '' : value.replace('https', 'coucou');
    };
});
