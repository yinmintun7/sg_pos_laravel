var app = angular.module("myApp", []);
app.controller("myCtrl", function ($scope, $http) {
  $scope.showCategories = true;
  $scope.showItems = false;
  $scope.items = [];
  $scope.base_url = base_url;
  $scope.shift_id = shiftId;
  $scope.categories = [];
  $scope.itemsData = [];
  $scope.allItems = [];
  $scope.orderDetail = [];
  $scope.subTotal = 0;
  $scope.init = function (id) {
    // $('.loading').show();
   var data = {
     order_id:id,
    };
    $http({
        method: "POST",
        url: base_url + "get-order-items",
        data: data,
      }).then(
        function (response) {
          if (response.status == 200) {
            $('.loading').hide();
            $scope.itemsData = response.data.data;
            $scope.fetchChildCategory(0);
            $scope.fetchAllItems();
            $scope.calculateTotalAmount();
          } else {
            console.log("Error:" + response.status);
          }
        },
      ).catch(function (error) {
        if (error.status === 422) {
            var errors = error.data.errors;
            angular.forEach(errors, function (error) {
                $scope.showError(error);
            });
        } else {
            console.error("An error occurred:", error);
        }
    });
    };

    $scope.returnBack = function(){
      $scope.fetchChildCategory(0);
    }
    $scope.getParentCategories = function (id) {
      $scope.categories = [];
      $scope.fetchChildCategory(id);
    };
    $scope.getParentCategory = function () {
      $scope.fetchChildCategory(0);
    };
    $scope.fetchChildCategory = function (parent_id) {
        $('.loading').show();
      $scope.showCategories = true;
      $scope.showItems = false;
      var data = {
        parent_id: parent_id,
      };
      var url = base_url + "get-category";
      $http({
        method: "POST",
        url: url,
        data: data,
      }).then(
        function (response) {
          if (response.status == 200) {
            $('.loading').hide();
            if (response.data.data.length <= 0) {
              $scope.showCategories = false;
              $scope.showItems = true;
              $scope.fetchItems(parent_id);
            } else {
              $scope.categories = response.data.data;
              console.log($scope.categories);
            }
          } else {
            alert("Something Wrong");
          }
        },
        function (error) {
          console.error(error);
        }
      );
    };

    $scope.fetchItems = function (category_id) {
        $('.loading').show();
      var data = {
        category_id: category_id,
      };
      var url = base_url + "get-item-by-cat";
      $http({
        method: "POST",
        url: url,
        data: data,
      }).then(
        function (response) {
          if (response.status == 200) {
            $('.loading').hide();
            $scope.items = response.data.data;
          } else {
            alert("Something Wrong");
          }
        },
        function (error) {
          console.error(error);
        }
      );
    };

    $scope.fetchAllItems = function () {
      var data = {};
      var url = base_url + "get-item";
      $http({
        method: "POST",
        url: url,
        data: data,
      }).then(
        function (response) {
          if (response.status == 200) {
            $scope.allItems = response.data.data;
          } else {
            alert("Something Wrong");
          }
        },
        function (error) {
          console.error(error);
        }
      );
    };

    $scope.getItemData = function (item_id) {
        $('.loading').show();
      var data = {
        item_id: item_id,
      };
      var url = base_url + "get-item-data";
      $http({
        method: "POST",
        url: url,
        data: data,
      }).then(
        function (response) {
          if (response.status == 200) {
            $('.loading').hide();
            var item_exist = false;
            var updatedItems = $scope.itemsData.map((item) => {
              if (item.id === item_id) {
                item_exist = true;
                return {
                  ...item,
                  quantity: item.quantity + 1,
                };
              }
              return item;
            });
            if (item_exist) {
              $scope.itemsData = updatedItems;
            } else {
              $scope.itemsData.push(response.data.data);
            }
          } else {
            alert("Something Wrong");
          }
          $scope.calculateTotalAmount();
        },
        function (error) {
          console.error(error);
        }
      );
    };
    $scope.removeItem = function (item_id) {
      $scope.itemsData = $scope.itemsData.filter((item) => item.id != item_id);
      $scope.calculateTotalAmount();
    };
    $scope.quantityPlus = function (item_id) {
      const updatedItems = $scope.itemsData.map((item) => {
        if (item.id === item_id) {
          const newQuantity = item.quantity + 1;
          //const newAmount = (item.price - item.discount) * newQuantity;

          return {
            ...item,
            quantity: newQuantity,
            //amount: newAmount,
          };
        }
        return item;
      });
      $scope.itemsData = updatedItems;
      $scope.calculateTotalAmount();
    };
    $scope.quantityMinus = function (item_id) {
      const updatedItems = $scope.itemsData.map((item) => {
        if (item.id === item_id && item.quantity != 1) {
          const newQuantity = item.quantity - 1;
          //const newAmount = (item.price - item.discount) * newQuantity;
          return {
            ...item,
            quantity: newQuantity,
            //amount: newAmount,
          };
        }
        return item;
      });
      $scope.itemsData = updatedItems;
       $scope.calculateTotalAmount();
    };

    $scope.calculateTotalAmount = function () {
      $scope.subTotal = 0;
      for (var i = 0; i < $scope.itemsData.length; i++) {
        // console.log($scope.itemsData);
        $scope.subTotal +=
          ($scope.itemsData[i].price - $scope.itemsData[i].discount) *
          $scope.itemsData[i].quantity;
      }
    };
    // for item search start//
    $scope.itemSearch = function () {
      if ($scope.search_item == "") {
        $scope.fetchChildCategory(0);
        $scope.showCategories = true;
        $scope.showItems = false;
      } else {
        $scope.showCategories = false;
        $scope.showItems = true;
        $scope.items = [];
        $scope.items = $scope.allItems.filter(function (item) {
          return (
            item.code_no && item.code_no.toString().startsWith($scope.search_item)
          );
        });
      }
    };
    // // for item search end////////////////////////////////////////////////////////////////

    // for make order start//
    $scope.makeOrder = function (id) {
      var orderDetails = {
        id:id,
        items: $scope.itemsData,
        subTotal: $scope.subTotal,
        // shift_id: shiftId,
      };

      $http({
        method: "POST",
        url: base_url + "update-order",
        data: orderDetails,
      }).then(
        function (response) {
          if (response.status == 200) {
             window.location.href = base_url + "order-list";
          } else {
            // window.location.href = base_url + 'sg_frontend/order?err=shift';
          }
        },
        function (error) {
          console.error(error);
        }
      ).catch(function (error) {
        if (error.status === 422) {
            var errors = error.data.errors;
            angular.forEach(errors, function (error) {
                new PNotify({
                    title: 'Error!',
                    text: error,
                    type: 'error',
                    styling: 'bootstrap3'
                });
            });
        } else {
            console.error("An error occurred:", error);
        }
    });
    };

    $scope.showError = function(error) {
        new PNotify({
            title: 'Error!',
            text: error,
            type: 'error',
            styling: 'bootstrap3'
        });
    };

});
