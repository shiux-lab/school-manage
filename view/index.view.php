<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <!-- Meta Start -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成员管理</title>
    <!-- <link rel="stylesheet" href="static/css/bootstrap3.min.css"> -->
    <!-- Meta End -->

    <!-- Link Start -->
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/all.min.css">
    <link rel="stylesheet" href="static/css/bootstrap-4.min.css">
    <link rel="stylesheet" href="static/css/main.css">
    <!-- Link End -->
</head>

<body>
<!-- App Start -->
<div id="app">
<?php
session_start();

if (empty($_SESSION['admin'][1])) {
    echo '<a class="btn btn-dark" id="login" href="javascript:" @click="loginBtn()"><i class="fas fa-sign-out-alt"></i> 登录</a>';
} else {
    ?>
    <div class="container table-responsive">
        <table class="table text-center table-striped">
            <caption>成员管理系统 , 欢迎您!管理员 <strong class="fas fa-user"> <?php echo $_SESSION['admin'][0] ?></strong>
            </caption>
            <thead class="thead-dark">
            <tr class="row">
                <th class="col-1"><label for="all"><input type="checkbox" :checked="key" @click="allBtn()"> 选择</label>
                </th>
                <th class="col-1" @click="sort('id')"><i class="fas fa-sort-amount-down"></i> 编号 <i class="fas"
                                                                                                    :class="[isClick ? 'fa-angle-up' : 'fa-angle-down']"
                                                                                                    v-if="type=='id'"></i>
                </th>
                <th class="col-2" @click="sort('name')"><i class="fas fa-user"></i> 姓名 <i class="fas"
                                                                                          :class="[isClick ? 'fa-angle-up' : 'fa-angle-down']"
                                                                                          v-if="type=='name'"></i></th>
                <th class="col-1" @click="sort('sex')"><i class="fab fa-android"></i> 性别 <i class="fas"
                                                                                            :class="[isClick ? 'fa-angle-up' : 'fa-angle-down']"
                                                                                            v-if="type=='sex'"></i></th>
                <th class="col-2" @click="sort('tel')"><i class="fas fa-phone"></i> 电话 <i class="fas"
                                                                                          :class="[isClick ? 'fa-angle-up' : 'fa-angle-down']"
                                                                                          v-if="type=='tel'"></i></th>
                <th class="col-1" @click="sort('room')"><i class="fas fa-door-closed"></i> 宿舍 <i class="fas"
                                                                                                 :class="[isClick ? 'fa-angle-up' : 'fa-angle-down']"
                                                                                                 v-if="type=='room'"></i>
                </th>
                <th class="col-3" @click="sort('major')"><i class="fas fa-project-diagram"></i> 专业 <i class="fas"
                                                                                                      :class="[isClick ? 'fa-angle-up' : 'fa-angle-down']"
                                                                                                      v-if="type=='major'"></i>
                </th>
                <th class="col-1"><i class="fas fa-project-diagram"></i> 操作</th>
            </tr>
            </thead>
            <tbody>
            <tr class="row" v-for="(item,index) in currentData" :key="index">
                <td class="col-1"><input type="checkbox" @click="onCheck(index)" :checked="keys[index].status"></td>
                <td class="col-1">{{ item.id }}</td>
                <td class="col-2">{{ item.name }}</td>
                <td class="col-1">{{ Number(item.sex) ? '男' : '女' }}</td>
                <td class="col-2">{{ item.tel }}</td>
                <td class="col-1">{{ item.room }}</td>
                <td class="col-3">{{ item.major }}</td>
                <td class="col-1">
                    <div class="dropdown">
                        <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle"
                                data-toggle="dropdown"><i class="fas fa-tools"></i> 操作
                        </button>
                        <div class="dropdown-menu">
                            <a href="javascript:" class="dropdown-item" @click="alter(item.id,index)"><i
                                        class="fas fa-pencil-alt"></i> 修改</a>
                            <a href="javascript:" class="dropdown-item" @click="delOneBtn(item.id)"><i
                                        class="fas fa-trash"></i> 删除</a>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr class="row row-cols-1" v-if="list.length > pages.pageNum">
                <td>
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary btn-sm prev" v-if="pages.currentPage !== 1"
                                @click="switchPage(pages.currentPage-1)"><i class="fas fa-caret-left"></i></button>
                        <button class="btn btn-dark btn-sm" :class="[index === pages.currentPage ? 'disabled' : '']"
                                v-for="index in pages.currentPage" @click="switchPage(index)">{{ index }}
                        </button>
                        <button class="btn btn-dark btn-sm disabled" v-if="pages.totalPage > pages.showPage"><i
                                    class="fas fa-ellipsis-h"></i></button>
                        <button class="btn btn-dark btn-sm" v-if="pages.totalPage !== pages.currentPage"
                                @click="switchPage(pages.totalPage)">{{ pages.totalPage }}
                        </button>
                        <button class="btn btn-outline-secondary btn-sm next"
                                v-if="pages.currentPage !== pages.totalPage" @click="switchPage(pages.currentPage+1)"><i
                                    class="fas fa-caret-right"></i></button>
                    </div>
                </td>
            </tr>
            <tr class="row row-cols-1">
                <td>
                    <div class="btn-group">
                        <a class="btn btn-outline-dark" id="add" href="javascript:" @click="addBtn()"><i
                                    class="fas fa-plus-circle"></i> 添加成员</a>
                        <a class="btn btn-outline-dark" id="delete" href="javascript:" v-if="list.length"
                           @click="delBtn()"><i class="fas fa-trash-alt"></i> 删除成员</a>
                        <a class="btn btn-dark" id="login" href="javascript:" @click="logoutBtn()"><i
                                    class="fas fa-sign-out-alt"></i> 注销</a>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php
}
?>
</div>
<!-- App End -->
<!-- Script Start -->
<script src="static/js/jquery.min.js"></script>
<script src="static/js/sweetalert2.min.js"></script>
<script src="static/js/vue.min.js"></script>
<script src="static/js/popper.min.js"></script>
<script src="static/js/main.js"></script>
<script src="static/js/bootstrap.min.js"></script>
<!-- Script End -->
</body>

</html>