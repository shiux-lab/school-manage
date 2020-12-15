<?php
namespace app\student\view;
class Msg
{
  public function displayAll()
  {
?>
    <div class="container table-responsive">
      <table class="table text-center table-striped">
        <caption>成员管理系统</caption>
        <thead class="thead-dark">
          <tr class="row">
            <th class="col-1"><label for="all"><input type="checkbox" :checked="key" @click="allBtn()"> 选择</label></th>
            <th class="col-1" @click="sort('id')"><i class="fas fa-sort-amount-down"></i> 编号 <i class="fas" :class="[isClick ? 'fa-angle-up' : 'fa-angle-down']" v-if="type=='id'"></i></th>
            <th class="col-2" @click="sort('name')"><i class="fas fa-user"></i> 姓名 <i class="fas" :class="[isClick ? 'fa-angle-up' : 'fa-angle-down']" v-if="type=='name'"></i></th>
            <th class="col-1" @click="sort('sex')"><i class="fab fa-android"></i> 性别 <i class="fas" :class="[isClick ? 'fa-angle-up' : 'fa-angle-down']" v-if="type=='sex'"></i></th>
            <th class="col-2" @click="sort('tel')"><i class="fas fa-phone"></i> 电话 <i class="fas" :class="[isClick ? 'fa-angle-up' : 'fa-angle-down']" v-if="type=='tel'"></i></th>
            <th class="col-1" @click="sort('room')"><i class="fas fa-door-closed"></i> 宿舍 <i class="fas" :class="[isClick ? 'fa-angle-up' : 'fa-angle-down']" v-if="type=='room'"></i></th>
            <th class="col-2" @click="sort('major')"><i class="fas fa-project-diagram"></i> 专业 <i class="fas" :class="[isClick ? 'fa-angle-up' : 'fa-angle-down']" v-if="type=='major'"></i></th>
            <th class="col-2"><i class="fas fa-project-diagram"></i> 操作</th>
          </tr>
        </thead>
        <tbody>
          <tr class="row" v-for="(item,index) in currentData" :key="index">
            <td class="col-1"><input type="checkbox" @click="onCheck(index)" :checked="keys[index].status"></td>
            <td class="col-1">{{ item.id }}</td>
            <td class="col-2">{{ item.name }}</td>
            <td class="col-1">{{ item.sex }}</td>
            <td class="col-2">{{ item.tel }}</td>
            <td class="col-1">{{ item.room }}</td>
            <td class="col-2">{{ item.major }}</td>
            <td class="col-2"><button class="btn btn-sm btn-dark" @click="alter(item.id,index)">修改</button><i class="mx-2"></i><button class="btn btn-sm btn-dark" @click="delOneBtn(item.id)">删除</button></td>
          </tr>
          <tr class="row row-cols-1" v-if="list.length > pages.pageNum">
            <td><a class="btn btn-secondary m-2 page-item" :class="[index === pages.currentPage ? 'disabled' : '']" v-for="index in pages.totalPage" @click="switchPage(index)">{{ index }}</a></td>
          </tr>
          <tr class="row row-cols-1">
            <td>
              <a class="btn btn-dark" id="add" href="javascript:" @click="addBtn()">添加成员</a>
              <a class="btn btn-dark" id="delete" href="javascript:" v-if="list.length" @click="delBtn()">删除成员</a>
              <a class="btn btn-dark" id="login" href="javascript:" @click="loginBtn()">登录注册</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
<?php
  }

  public function displayDelMsg($result)
  {
    return $result ? '删除成功' : '删除失败';
  }

  public function displayAddMsg($result)
  {
    if ($result)
      return '添加成功';
    else {
      header('HTTP/1.1 502 Bad Gateway');
      return '添加失败';
    }
  }

  public function displayLoginMsg($result)
  {
    if ($result)
      return '登录成功';
    else {
      header('HTTP/1.1 502 Bad Gateway');
      return '登录失败,请重新登录';
    }
  }

  public function displayLogoutMsg($result)
  {
    if ($result)
      return '注销成功';
    else {
      header('HTTP/1.1 502 Bad Gateway');
      return '注销失败';
    }
  }

  public function displayAlterMsg($result)
  {
    if ($result)
      return '修改成功';
    else {
      header('HTTP/1.1 502 Bad Gateway');
      return '修改失败';
    }
  }
}
