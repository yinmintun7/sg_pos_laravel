var app = angular.module('myApp', []);
app.controller('myCtrl', function($scope) {
    $scope.UserInputType = '';
    $scope.username = '';
    $scope.password = '';

    $scope.initusername = function(){
        angular.element('[name="username"]').focus();
        $scope.usernameFocus();
    }
    $scope.initpassword = function(){
        angular.element('[name="password"]').focus();
        $scope.passwordFocus();
    }

    $scope.Login = function(){
        $('#submit').submit();
    }

    $scope.usernameFocus = function(){
       $scope.UserInputType = 'username'
    }
    $scope.passwordFocus = function(){
        $scope.UserInputType = 'password'
     }
    $scope.numberClick = function(number){
        var input_num = parseInt(number);
        if($scope.UserInputType == '' || $scope.UserInputType == 'username'){
            $scope.username = $scope.username + input_num;
        }else{
            $scope.password = $scope.password + input_num;
        }
    }

    $scope.delete = function(){
        if($scope.UserInputType == 'username'){
            $scope.username = $scope.username.slice(0, -1);
        }else{
            $scope.password = $scope.password.slice(0, -1);
        }
     }
  //  $scope.Login = function(){
    //     if($scope.username == '' || $scope.password == ''){
    //         alert ('fill username and password!');
    //     }else{
    //         var data = {
    //            username:$scope.username,
    //            password:$scope.password
    //           };
    //           $http({
    //             method: "POST",
    //             url: base_url + "api/postFrontendlogin",
    //             data: data,
    //           }).then(
    //             function (response) {
    //               if (response.status == 200) {
    //                alert('success')
    //               } else {
    //                 console.log("Error:" + response.status);
    //               }
    //             },
    //             function (error) {
    //               console.error(error);
    //             }
    //           );
    //     }

    // }

});
