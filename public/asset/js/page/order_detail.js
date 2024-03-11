var app = angular.module("myApp", []);
app.controller("myCtrl", function ($scope, $http) {
  $scope.orderDetal=[];
  $scope.settingData = [];
  $scope.init = function () {
  };
  $scope.orderDetail = function (orderId) {
    $scope.getSettingData();
    var data = {
      orderId:orderId,
      shift_id:shiftId
    };
    var url = base_url + "get-order-detail";
    $http({
      method: "POST",
      url: url,
      data:data,
    }).then(
      function (response) {
        if (response.status == 200) {
          $scope.orderDetail = [];
          $scope.orderDetail = response.data.
          data;
          $scope.qty = 0;
          $scope.orderDetail.order_detail.forEach(item => {
              $scope.qty += item.quantity;
          });

        } else {
          console.log("Error:" + response.status);
        }
      },
      function (error) {
        console.error(error);
      }
    );
  };

  $scope.getSettingData = function () {
    var url = base_url + "get-setting-data";
    $http({
      method: "POST",
      url: url,
    }).then(
      function (response) {
        if (response.status == 200) {
          $scope.settingData = response.data.
          data;
        } else {
          console.log("Error:" + response.status);
        }
      },
      function (error) {
        console.error(error);
      }
    );
  };

   $scope.convertDateFormat = function(inputDate) {
    var date = new Date(inputDate);
    var day = date.getUTCDate();
    var month = date.getUTCMonth() + 1;
    var year = date.getUTCFullYear();
    var formattedDate = (day < 10 ? '0' : '') + day + '/' + (month < 10 ? '0' : '') + month + '/' + year;
    return formattedDate;
}

$scope.convertTimeFormat = function(inputDate) {
    var date = new Date(inputDate);
    var hours = date.getUTCHours();
    var minutes = date.getUTCMinutes();
    var am_pm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12;
    var formattedTime = (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes + ' ' + am_pm;
    return formattedTime;
}
});
