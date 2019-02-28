<nav class="navbar navbar-static-top" role="navigation">
    <div class="navbar-header">
        <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
            <i class="fa fa-reorder"></i>
        </button>
        <a href="{:url('index/index')}" class="navbar-brand">THANHOO</a>
    </div>
    <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <a href="{:url('index/logout')}">
                    <i class="fa fa-sign-out"></i> 退出
                </a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li>
                <a aria-expanded="false" role="button" href="#" target="_blank"> 首页</a>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 基础设置 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('city/index', ['ptype'=>1])}">基础设置列表</a></li>
                    <li><a href="{:url('city/index', ['ptype'=>3])}">app版本控制</a></li>
                    </li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 产品管理 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('city/index', ['ptype'=>1])}">标的</a></li>
                    <li><a href="{:url('city/index', ['ptype'=>3])}">持仓</a></li>
                    <li><a href="{:url('product/index')}">借款</a>
                    </li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 财务管理 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('product/index')}">收息记录</a></li>
                    <li><a href="{:url('product/add')}">派息/还款记录</a></li>
                    <li><a href="{:url('product/add')}">邀请奖励记录</a></li>
                    <li><a href="{:url('product/add')}">补账记录</a></li>
                    <li><a href="{:url('product/add')}">充值记录</a></li>
                    <li><a href="{:url('product/add')}">体现记录</a></li>
                    </li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 用户管理 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('order/index')}">用户列表</a></li>
                    <li><a href="{:url('order/input')}">新增幽灵账户</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 红包管理 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('order/index')}">红包派送计划</a></li>
                    <li><a href="{:url('order/input')}">红包领取记录</a></li>
                    <li><a href="{:url('order/input')}">渠道管理</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 积分管理 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('order/index')}">积分派送计划</a></li>
                    <li><a href="{:url('order/input')}">商品品牌管理</a></li>
                    <li><a href="{:url('order/input')}">积分商品管理</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 权限管理 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('order/index')}">权限管理</a></li>
                    <li><a href="{:url('order/input')}">资源管理</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 管理员 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('order/index')}">管理员列表</a></li>
                    <li><a href="{:url('order/input')}">管理员/角色管理</a></li>
                    <li><a href="{:url('order/input')}">角色管理</a></li>
                    <li><a href="{:url('order/input')}">操作日志</a></li>
                </ul>
            </li>
        </ul>

    </div>
</nav>



<nav class="navbar navbar-static-top" style="border-top:1px solid #f0f0f0">
    <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-nav navbar-right">
            <li>
                <a aria-expanded="false" role="button" href="#" target="_blank"> 首页</a>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 基础设置 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('city/index', ['ptype'=>1])}">基础设置列表</a></li>
                    <li><a href="{:url('city/index', ['ptype'=>3])}">app版本控制</a></li>
                    </li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 产品管理 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('city/index', ['ptype'=>1])}">标的</a></li>
                    <li><a href="{:url('city/index', ['ptype'=>3])}">持仓</a></li>
                    <li><a href="{:url('product/index')}">借款</a>
                    </li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 财务管理 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('product/index')}">收息记录</a></li>
                    <li><a href="{:url('product/add')}">派息/还款记录</a></li>
                    <li><a href="{:url('product/add')}">邀请奖励记录</a></li>
                    <li><a href="{:url('product/add')}">补账记录</a></li>
                    <li><a href="{:url('product/add')}">充值记录</a></li>
                    <li><a href="{:url('product/add')}">体现记录</a></li>
                    </li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 用户管理 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('order/index')}">用户列表</a></li>
                    <li><a href="{:url('order/input')}">新增幽灵账户</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 红包管理 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('order/index')}">红包派送计划</a></li>
                    <li><a href="{:url('order/input')}">红包领取记录</a></li>
                    <li><a href="{:url('order/input')}">渠道管理</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 积分管理 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('order/index')}">积分派送计划</a></li>
                    <li><a href="{:url('order/input')}">商品品牌管理</a></li>
                    <li><a href="{:url('order/input')}">积分商品管理</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 权限管理 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('order/index')}">权限管理</a></li>
                    <li><a href="{:url('order/input')}">资源管理</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> 管理员 <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{:url('order/index')}">管理员列表</a></li>
                    <li><a href="{:url('order/input')}">管理员/角色管理</a></li>
                    <li><a href="{:url('order/input')}">角色管理</a></li>
                    <li><a href="{:url('order/input')}">操作日志</a></li>
                </ul>
            </li>
        </ul>

    </div>
</nav>