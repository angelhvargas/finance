(function(angular){

    function userListController($scope, $element, $attrs) {
        var ctrl = this;
      
        // This would be loaded by $http etc.
        ctrl.list = [
          {
            name: 'Superman',
            location: ''
          },
          {
            name: 'Batman',
            location: 'Wayne Manor'
          }
        ];
      
        ctrl.updateUser = function(user, prop, value) {
          user[prop] = value;
        };
      
        ctrl.deleteUser = function(user) {
          var idx = ctrl.list.indexOf(user);
          if (idx >= 0) {
            ctrl.list.splice(idx, 1);
          }
        };
      }
      
      angular.module('financeApp').component('userList', {
        templateUrl: 'userList.html',
        controller: userListController
      });

})(window.angular)