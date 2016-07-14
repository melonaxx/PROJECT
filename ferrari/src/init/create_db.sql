
set names utf8;
-- --基础服务---/*{{{*/

DROP TABLE IF EXISTS id_genter;
CREATE TABLE id_genter
(
    id             int(11) NOT NULL,
    obj            varchar(30),
    step           int(11)
) engine = innodb;

insert into id_genter(id, obj, step) values(65535, 'other', 1);
--/*}}}*/

-- --用户表---/*{{{*/
DROP TABLE IF EXISTS user;
CREATE TABLE user
(
    id              int             NOT NULL PRIMARY KEY COMMENT '用户ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    name            varchar(32)     NOT NULL COMMENT '用户名',
    avatar          varchar(128)    NOT NULL DEFAULT '' COMMENT '用户头像',
    status          enum('Z','S','T') NOT NULL default 'Z' COMMENT '用户状态 Z:正常,S:删除,T:停用'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '用户表';
alter table `user` add UNIQUE uniq_idx_name (name);
--/*}}}*/

-- --用户安全表---/*{{{*/
DROP TABLE IF EXISTS security;
CREATE TABLE security
(
    userid          int             NOT NULL PRIMARY KEY COMMENT '用户ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    token           varchar(32)     NOT NULL COMMENT '密钥',
    password        varchar(64)     NOT NULL COMMENT '用户密码',
    strength        tinyint         NOT NULL DEFAULT 0 COMMENT '密码强度'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '用户安全表';
--/*}}}*/

-- --用户信息表---/*{{{*/
DROP TABLE IF EXISTS userinfo;
CREATE TABLE userinfo
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    userid          int(11)         not null COMMENT '用户ID',
    authority       varchar(300)    not null DEFAULT '' COMMENT '权限',
    realname        varchar(21)     not null DEFAULT '' COMMENT '真实姓名',
    departmentid    int(11)         not null DEFAULT 0 COMMENT '部门ID',
    salesid         int(11)         not null DEFAULT 0 COMMENT '公司渠道ID',
    purchasecompid  int(11)         not null DEFAULT 0 COMMENT '公司ID',
    number          varchar(20)     not null DEFAULT '' COMMENT '员工编号',
    comment         varchar(300)    NOT NULL DEFAULT '' COMMENT '备注',
    tel             varchar(11)     NOT NULL DEFAULT '' COMMENT '联系方式',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '用户信息表';
--/*}}}*/
-- 用户角色关系表
DROP TABLE IF EXISTS userrole;
CREATE TABLE userrole (
    id              int(11)         NOT NULL PRIMARY KEY,
    userid          int(11)         NOT NULL COMMENT '用户ID',
    roleid          int(11)         NOT NULL COMMENT '角色ID',
    createtime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
    updatetime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间'
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='用户角色关系表';
-- --登陆日志表---/*{{{*/
DROP TABLE IF EXISTS loginlog;
CREATE TABLE loginlog
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    userid          int(11)         not null COMMENT '用户ID',
    loginip         varchar(32)     not null COMMENT '登陆IP',
    logintime       datetime       not null COMMENT '登陆时间',
    logouttime      datetime       not null COMMENT '退出时间',
    loginstatus     tinyint default 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '登陆日志表';
--/*}}}*/

-- --角色表---/*{{{*/
DROP TABLE IF EXISTS role;
CREATE TABLE role
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '角色ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    name            varchar(50)     NOT NULL COMMENT '角色名称',
    comment         varchar(300)    NOT NULL COMMENT '备注',
    authority       varchar(300)    NOT NULL COMMENT '权限',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'Y' COMMENT '状态: Y 正常, N 删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '角色表';
ALTER TABLE `role` add UNIQUE uniq_idx_name (name);
--/*}}}*/

-- --权限表---/*{{{*/
DROP TABLE IF EXISTS authority;
CREATE TABLE authority
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '权限ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    name            varchar(50)     NOT NULL COMMENT '权限名称',
    value           varchar(300)    NOT NULL COMMENT '权限值',
    moduleid        int(11)         NOT NULL COMMENT '模块id',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'Y' COMMENT '状态: Y 正常, N 删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '权限表';
ALTER TABLE `authority` add UNIQUE uniq_idx_name (name);
--/*}}}*/

-- --部门表---/*{{{*/
DROP TABLE IF EXISTS department;
CREATE TABLE department
(
    id              int(11)         NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '部门ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    parent_id       int(11)         NOT NULL DEFAULT '0' COMMENT '上级部门ID',
    name            varchar(30)     NOT NULL COMMENT '部门名称',
    comment         varchar(300)    NOT NULL COMMENT '备注',
    isdelete        enum('Y','D')   NOT NULL DEFAULT 'Y' COMMENT '状态: Y正常, D已删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '部门表';
--/*}}}*/

-- --模块表---/*{{{*/
DROP TABLE IF EXISTS module;
CREATE TABLE module
(
    id              int(11)         NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '模块ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    value           smallint        NOT NULL COMMENT '模块值',
    name            varchar(30)     NOT NULL COMMENT '模块名称',
    isdelete        enum('Y','D')   NOT NULL DEFAULT 'Y' COMMENT '状态: Y正常, D已删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '模块表';
--/*}}}*/

-- --操作记录表---/*{{{*/
DROP TABLE IF EXISTS history;
CREATE TABLE history
(
    id              int(11) NOT NULL primary key,
    createtime      datetime NOT NULL,
    updatetime      datetime NOT NULL,
    userid          int(11) NOT NULL,
    uri             varchar(4096) NOT NULL,
    data            varchar(4096) NOT NULL,
    status          int(11) NOT NULL
) engine=innodb default CHARACTER SET UTF8;
--/*}}}*/

-- --仓库基本信息表----/*{{{*/
DROP TABLE IF EXISTS storeinfo;
CREATE TABLE storeinfo
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '仓库ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    number          varchar(16)     NOT NULL COMMENT '仓库编号',
    name            varchar(32)     NOT NULL DEFAULT '默认仓库'  COMMENT '仓库名称',
    stateid         mediumint       NOT NULL DEFAULT '0' COMMENT '所在省份ID',
    cityid          mediumint       NOT NULL DEFAULT '0' COMMENT '所在城市ID',
    districtid      mediumint       NOT NULL DEFAULT '0' COMMENT '所在区域ID',
    address         varchar(128)    NOT NULL DEFAULT '' COMMENT '详细地址',
    contactname     varchar(16)     NOT NULL DEFAULT '' COMMENT '联系人',
    telphone        varchar(11)     NOT NULL DEFAULT '' COMMENT '固定电话',
    mobile          varchar(11)     NOT NULL DEFAULT '' COMMENT '手机',
    describes       varchar(128)    NOT NULL DEFAULT '' COMMENT '仓库备注',
    storestatus     enum('Normal','Default','Stop','Delete')        NOT NULL DEFAULT 'Normal' COMMENT '仓库状态：正常的，默认的，停用的，删除的',
    storetype       enum('Sales','Defective','Customer','Purchase') NOT NULL DEFAULT 'Sales' COMMENT '仓库属性：销售仓, 次品仓, 售后仓, 采购仓',
    staffid         int(11)         NOT NULL DEFAULT '0' COMMENT '建仓员工',
    total           int(11)         NOT NULL DEFAULT '0' COMMENT '库区数量',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '仓库基本信息表';
--/*}}}*/

-- --仓库 - 商品信息----/*{{{*/
DROP TABLE IF EXISTS strproduct;
CREATE TABLE strproduct
(
    id              int             NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    storeid         int             NOT NULL DEFAULT '0' COMMENT '仓库ID',
    productid       int             NOT NULL DEFAULT '0' COMMENT '商品ID',
    totalreal       mediumint       NOT NULL DEFAULT '0' COMMENT '商品数量: 实际库存',
    totalway        mediumint       NOT NULL DEFAULT '0' COMMENT '商品数量: 在途',
    totallock       mediumint       NOT NULL DEFAULT '0' COMMENT '商品数量: 已锁定',
    totalavailable  mediumint       NOT NULL DEFAULT '0' COMMENT '商品数量: 可用',
    totalproduction mediumint       NOT NULL DEFAULT '0' COMMENT '商品数量: 生产中',
    iswarning       enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否有预警',
    up              mediumint       NOT NULL DEFAULT '0' COMMENT '上限',
    low             mediumint       NOT NULL DEFAULT '0' COMMENT '下限',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '仓库 - 商品信息';
--/*}}}*/

-- --仓库发货地区表---/*{{{*/
DROP TABLE IF EXISTS straddress;
CREATE TABLE straddress
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    storeid         int(11)         NOT NULL DEFAULT '0' COMMENT '仓库ID',
    stateid         int(11)         NOT NULL DEFAULT '0' COMMENT '所在省份ID',
    cityid          int(11)         NOT NULL DEFAULT '0' COMMENT '所在城市ID',
    status          tinyint(4)      NOT NULL DEFAULT 0 COMMENT '0:正常;1:删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '仓库发货地区表';
--/*}}}*/

-- --库区、货架、货位信息表---/*{{{*/
DROP TABLE IF EXISTS strlocation;
CREATE TABLE strlocation
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '区架位ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    storeid         int(11)         NOT NULL DEFAULT '0' COMMENT '仓库ID',
    placeno         varchar(32)     NOT NULL COMMENT '区架位信息编码',
    comment         text            NOT NULL DEFAULT '' COMMENT '货位信息备注',
    locationtype    enum('Area','Shelves','Location') NOT NULL DEFAULT 'Area' COMMENT '货位类型：库区, 货架, 货位',
    parentid        int(11)         NOT NULL DEFAULT '0' COMMENT '上级ID',
    totalcheck      mediumint(8)    NOT NULL DEFAULT '0' COMMENT '下级数量',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '库区、货架、货位信息表';
--/*}}}*/

-- --仓库 - 商品存放位置关联表---/*{{{*/
DROP TABLE IF EXISTS strrelated;
CREATE TABLE strrelated
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    storeid         int(11)         NOT NULL DEFAULT '0' COMMENT '仓库ID',
    productid       bigint          NOT NULL DEFAULT '0' COMMENT '商品ID',
    areaid          int(11)         NOT NULL DEFAULT '0' COMMENT '库区ID',
    shelvesid       int(11)         NOT NULL DEFAULT '0' COMMENT '货架ID',
    locationid      int(11)         NOT NULL DEFAULT '0' COMMENT '货位ID',
    status          tinyint(4)      NOT NULL DEFAULT 0   COMMENT '0:正常；1：删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '仓库 - 商品存放位置关联表';
--/*}}}*/

-- --手动出库入库表---/*{{{*/
DROP TABLE IF EXISTS strmanual;
CREATE TABLE strmanual
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    productid       bigint          NOT NULL DEFAULT '0' COMMENT '商品ID',
    storeid         int(11)         NOT NULL DEFAULT '0' COMMENT '仓库ID',
    type            enum('Input','Output') NOT NULL DEFAULT 'Output' COMMENT '入库/出库',
    purposetype     enum('M','P','L','S','W') NOT NULL COMMENT '用途类型：生产/进货/盘点/销售/损耗',
    total           mediumint(8)    NOT NULL COMMENT '数量',
    staffid         int(11)         NOT NULL DEFAULT '0' COMMENT '操作人',
    comment         text            NOT NULL COMMENT '备注',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '仓库 - 手动出库入库表';
--/*}}}*/

-- --仓库 - 手动调拨单表---/*{{{*/
DROP TABLE IF EXISTS strmove;
CREATE TABLE strmove
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '调拨时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    movetype        enum('Product','Accessory') NOT NULL DEFAULT 'Product' COMMENT '调拨类型: Product 仅产品本身, Accessory 和配件一起',
    productid       bigint          NOT NULL DEFAULT '0' COMMENT '商品ID',
    moveoutid       int(11)         NOT NULL DEFAULT '0' COMMENT '调出库ID',
    moveinid        int(11)         NOT NULL DEFAULT '0' COMMENT '调入库ID',
    staffid         int(11)         NOT NULL DEFAULT '0' COMMENT '操作人',
    total           mediumint       NOT NULL DEFAULT '0' COMMENT '数量',
    comment         text            NOT NULL COMMENT '备注',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '仓库 - 手动调拨单表';
--/*}}}*/

-- --仓库 - 盘点单表---/*{{{*/
DROP TABLE IF EXISTS strcheck;
CREATE TABLE strcheck
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    storeid         int(11)         NOT NULL DEFAULT '0' COMMENT '仓库ID',
    productid       bigint          NOT NULL DEFAULT '0' COMMENT '商品ID',
    staffid         int(11)         NOT NULL DEFAULT '0' COMMENT '操作人',
    total           mediumint       NOT NULL DEFAULT '0' COMMENT '盈亏数量',
    comment         text            NOT NULL DEFAULT '' COMMENT '备注',
    oldtotal        mediumint       NOT NULL DEFAULT '0' COMMENT '盘点前数量',
    newtotal        mediumint       NOT NULL DEFAULT '0' COMMENT '盘点后数量',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '仓库 - 盘点单表';
--/*}}}*/

-- --商品信息表---/*{{{*/
DROP TABLE IF EXISTS product;
CREATE TABLE product
(
    productid       int(11)         NOT NULL PRIMARY KEY COMMENT '商品ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    number          varchar(16)     NOT NULL COMMENT '商品编码',
    parentid        bigint          NOT NULL DEFAULT '0' COMMENT '所属商品ID',
    name            varchar(256)    NOT NULL COMMENT '商品名称 或 规格名称',
    cost            float(12,2)     NOT NULL DEFAULT '0.00' COMMENT '平均成本',
    total           mediumint(8)    NOT NULL DEFAULT '0' COMMENT '商品数量',
    categoryid      int(11)         NOT NULL DEFAULT '0' COMMENT '分类ID',
    brandid         int(11)         NOT NULL DEFAULT '0' COMMENT '品牌ID',
    isstore         enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否记入库存',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否已经删除',
    havesku         enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否包含规格, Y包含规格, N独立商品',
    havecombination enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否有组合子商品',
    barcode         varchar(20)     NOT NULL COMMENT '条形码',
    serialnumber    varchar(20)     NOT NULL COMMENT '货号/序列号',
    producttype     enum('Virtual','Packaged','Real','Materials') NOT NULL DEFAULT 'Real' COMMENT '产品类型: 虚拟产品, 套装产品, 实体产品, 原材料',
    productquality  enum('New','Used') NOT NULL DEFAULT 'New' COMMENT '是否二手',
    version         int             NOT NULL DEFAULT '0' COMMENT '版本号',
    image           varchar(128)    NOT NULL DEFAULT '' COMMENT '商品主图',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品信息表';
--/*}}}*/

-- --商品详细信息表---/*{{{*/
DROP TABLE IF EXISTS proinfo;
CREATE TABLE proinfo
(
    id              bigint          NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    productid       int(11)         NOT NULL COMMENT '商品ID',
    pricetag        double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '吊牌价',
    pricepurchase   double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '进价',
    pricesell       double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '售价',
    pricetotal      double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '总价',
    weight          double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '重量',
    volume          double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '体积',
    unitid          int             NOT NULL DEFAULT '0' COMMENT '单位ID',
    formatid1       int             NOT NULL DEFAULT '0' COMMENT '规格名称ID 1',
    formatid2       int             NOT NULL DEFAULT '0' COMMENT '规格名称ID 2',
    formatid3       int             NOT NULL DEFAULT '0' COMMENT '规格名称ID 3',
    formatid4       int             NOT NULL DEFAULT '0' COMMENT '规格名称ID 4',
    formatid5       int             NOT NULL DEFAULT '0' COMMENT '规格名称ID 5',
    valueid1        int             NOT NULL DEFAULT '0' COMMENT '规格值ID 1',
    valueid2        int             NOT NULL DEFAULT '0' COMMENT '规格值ID 2',
    valueid3        int             NOT NULL DEFAULT '0' COMMENT '规格值ID 3',
    valueid4        int             NOT NULL DEFAULT '0' COMMENT '规格值ID 4',
    valueid5        int             NOT NULL DEFAULT '0' COMMENT '规格值ID 5',
    comment         text            NOT NULL COMMENT '备注',
    status          tinyint(4)      NOT NULL DEFAULT 0 COMMENT '0:正常；1:删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品详细信息表';
--/*}}}*/

-- --商品 - 第三方店铺商品关联表---/*{{{*/
DROP TABLE IF EXISTS prorelatedinfo;
CREATE TABLE prorelatedinfo
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    productid       bigint          NOT NULL DEFAULT '0' COMMENT '商品ID',
    userid          int(11)         NOT NULL DEFAULT '0' COMMENT '店铺老板ID',
    exterpid        bigint          NOT NULL DEFAULT '0' COMMENT '外部商品ID',
    exterpname      varchar(128)    NOT NULL COMMENT '外部商品名称',
    exterformatlist varchar(64)     NOT NULL COMMENT '外部规格标识',
    getdate         datetime        NOT NULL COMMENT '本次获取时间',
    lastdate        datetime        NOT NULL COMMENT '上次获取时间',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品 - 第三方店铺商品关联表';
--/*}}}*/

-- --商品 - 品牌表---/*{{{*/
DROP TABLE IF EXISTS probrand;
CREATE TABLE probrand
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '品牌ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    name            varchar(40)     NOT NULL COMMENT '品牌名称',
    parentid        int(11)         NOT NULL DEFAULT '0' COMMENT '上级品牌ID',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品 - 品牌表';
--/*}}}*/

-- --商品 - 分类表---/*{{{*/
DROP TABLE IF EXISTS procategory;
CREATE TABLE procategory
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '分类ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    name            varchar(40)     NOT NULL COMMENT '类目名称',
    parentid        int(11)         NOT NULL DEFAULT '0' COMMENT '上级分类ID',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品 - 分类表';
--/*}}}*/

-- --商品单位表---/*{{{*/
DROP TABLE IF EXISTS prounit;
CREATE TABLE prounit
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '单位ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    name            varchar(16)     NOT NULL COMMENT '单位名称',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否已删除',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品单位表';
--/*}}}*/

-- --商品 - 规格名称表---/*{{{*/
DROP TABLE IF EXISTS proformatename;
CREATE TABLE proformatename
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '规格名称ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    name            varchar(16)     NOT NULL COMMENT '规格名称',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否已删除',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品 - 规格名称表';
--/*}}}*/

-- --商品规格值表---/*{{{*/
DROP TABLE IF EXISTS proformatevalue;
CREATE TABLE proformatevalue
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '规格值ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    formatnameid    int(11)         NOT NULL DEFAULT '0' COMMENT '规格名称ID',
    choice          varchar(64)     NOT NULL COMMENT '规格可选值',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否已删除',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品规格值表';
--/*}}}*/

-- --商品图片表---/*{{{*/
DROP TABLE IF EXISTS proimage;
CREATE TABLE proimage
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '图片ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    productid       bigint          NOT NULL DEFAULT '0' COMMENT '商品ID',
    filename        varchar(256)    NOT NULL COMMENT '文件存放路径',
    width           smallint        NOT NULL DEFAULT '0' COMMENT '图片高度',
    height          smallint        NOT NULL DEFAULT '0' COMMENT '图片高度',
    url             varchar(256)    NOT NULL COMMENT '远程地址',
    filemd5         varchar(64)     NOT NULL COMMENT '图片名MD5值',
    sort            int             NOT NULL DEFAULT '0' COMMENT '图片排序',
    filesize        smallint        NOT NULL DEFAULT '0' COMMENT '文件大小',
    status          tinyint(4)      NOT NULL DEFAULT 0 COMMENT '0:正常；1：删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品图片表';
--/*}}}*/

-- --商品 - 销售信息表---/*{{{*/
DROP TABLE IF EXISTS prosale;
CREATE TABLE prosale
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    salesstatus     enum('Onsale','Soldout','Stop','Stockout') NOT NULL DEFAULT 'Onsale' COMMENT '销售状态: 在售, 下架, 停产, 缺货',
    productid       int(11)         NOT NULL COMMENT '商品ID',
    staffid         int(11)         NOT NULL DEFAULT '0' COMMENT '产品经理',
    salesnumber     mediumint       NOT NULL DEFAULT '0' COMMENT '销售数量',
    salesroom       double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '销售额',
    profit          double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '利润',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品 - 销售信息表';
--/*}}}*/

-- --商品 - 属性信息表---/*{{{*/
DROP TABLE IF EXISTS proattr;
CREATE TABLE proattr
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    productid       bigint          NOT NULL DEFAULT '0' COMMENT '商品ID',
    attrnameid      int(11)         NOT NULL DEFAULT '0' COMMENT '属性名称ID',
    attrvalueid     int(11)         NOT NULL DEFAULT '0' COMMENT '属性值ID',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品 - 属性信息表';
--/*}}}*/

-- --商品属性名称信息表---/*{{{*/
DROP TABLE IF EXISTS proattrname;
CREATE TABLE proattrname
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '属性名称ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    name            varchar(16)     NOT NULL COMMENT '属性名',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否已删除',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品属性名称信息表';
--/*}}}*/

-- --商品属性值信息表---/*{{{*/
DROP TABLE IF EXISTS proattrvalue;
CREATE TABLE proattrvalue
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '属性值ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    attribid        int(11)         NOT NULL DEFAULT '0' COMMENT '属性名称ID',
    optional        varchar(64)     NOT NULL COMMENT '属性可选值',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否已删除',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品属性值信息表';
--/*}}}*/

-- --商品 - 配件关系表---/*{{{*/
DROP TABLE IF EXISTS proparts;
CREATE TABLE proparts
(
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    productid       bigint          NOT NULL DEFAULT '0' COMMENT '商品ID',
    subid           bigint          NOT NULL DEFAULT '0' COMMENT '配件的商品ID',
    total           mediumint       NOT NULL DEFAULT '0' COMMENT '配件数量',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '商品 - 配件关系表';
--/*}}}*/

-- --公司销售渠道表---/*{{{*/
DROP TABLE IF EXISTS companysales;
CREATE TABLE companysales (
    id             int(11)         NOT NULL PRIMARY KEY COMMENT '公司渠道ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    name            varchar(64)     NOT NULL COMMENT '销售渠道名称',
    comment         varchar(256)    NOT NULL COMMENT '备注',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'Y' COMMENT '状态: Y 正常, N 删除',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '公司销售渠道表';
--/*}}}*/

-- --公司主表---/*{{{*/
DROP TABLE IF EXISTS company;
CREATE TABLE company (
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '公司ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    name            varchar(128)    NOT NULL COMMENT '公司名称',
    comment         varchar(256)    NOT NULL COMMENT '备注',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '状态: Y 正常, N 删除',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '公司主表';
--/*}}}*/

-- --银行账户表---/*{{{*/
DROP TABLE IF EXISTS financebank;
CREATE TABLE financebank (
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    name            varchar(32)     NOT NULL COMMENT '账户名称',
    number          varchar(32)     NOT NULL COMMENT '账户号码',
    comment         varchar(128)    NOT NULL COMMENT '备注',
    balance         double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '账户余额',
    type            enum('Cashier','Company','Secret','Special') NOT NULL DEFAULT 'Cashier' COMMENT '账户属性: 出纳账户, 公司账户, 私密账户, 特殊账户',
    isdefault       enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否默认账户',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除: Y删除, N正常',
    status          tinyint         NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '银行账户表';
--/*}}}*/

-- --采购供货商表---/*{{{*/
DROP TABLE IF EXISTS purchasesupplier;
CREATE TABLE purchasesupplier (
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '供应商ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    name            varchar(32)     NOT NULL COMMENT '供应商名称',
    number          varchar(32)     NOT NULL COMMENT '供应商编码',
    type            enum('Finished','Materials') NOT NULL DEFAULT 'Finished' COMMENT '供应商类型：Finished 成品供货商, Materials 原材料供货商',
    website         varchar(128)    NOT NULL COMMENT '网站网址',
    stateid         int(11)         NOT NULL DEFAULT '0' COMMENT '所在省份ID',
    cityid          int(11)         NOT NULL DEFAULT '0' COMMENT '所在城市ID',
    districtid      int(11)         NOT NULL DEFAULT '0' COMMENT '所在地区ID',
    address         varchar(256)    NOT NULL COMMENT '详细地址',
    postcode        varchar(6)      NOT NULL COMMENT '邮编',
    contactname     varchar(20)     NOT NULL COMMENT '联系人',
    phone           varchar(20)     NOT NULL COMMENT '固话',
    mobile          varchar(20)     NOT NULL COMMENT '手机',
    email           varchar(50)     NOT NULL COMMENT '电子邮件',
    tax             varchar(20)     NOT NULL COMMENT '税号',
    fax             varchar(20)     NOT NULL COMMENT '传真',
    bankname        varchar(128)    NOT NULL COMMENT '开户银行',
    banknumber      varchar(50)     NOT NULL COMMENT '银行账号',
    balance         double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '结算金额',
    comment         text            NOT NULL COMMENT '备注',
    level           enum('Primary','Alternative','Eliminate') NOT NULL COMMENT '供货商级别：Primary 主选供货商, Alternative 备选供货商, Eliminate 淘汰供货商',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除：N正常, Y已删除',
    status          tinyint         NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '采购供货商表';
--/*}}}*/

-- --采购单表---/*{{{*/
DROP TABLE IF EXISTS purchase;
CREATE TABLE purchase (
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '采购单ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    supplierid      int(10)         NOT NULL DEFAULT '0' COMMENT '供应商ID',
    number          varchar(40)     NOT NULL COMMENT '采购单编号',
    total           int(10)         NOT NULL DEFAULT '0' COMMENT '采购数量',
    storeid         int(10)         NOT NULL COMMENT '收货仓库ID',
    purchasecompanyid int(10)       NOT NULL COMMENT '采购公司ID',
    staffid         int(10)         NOT NULL DEFAULT '0' COMMENT '操作人',
    brief           varchar(256)    NOT NULL COMMENT '采购单摘要',
    actiondate      datetime        NOT NULL COMMENT '下单时间',
    comment         text            NOT NULL COMMENT '备注',
    taxprice        double(12,2)    not null DEFAULT '0.00' COMMENT '含税价',
    -- taxrate         tinyint         not null COMMENT '税率',
    tax             double(12,2)    not null DEFAULT '0.00' COMMENT '税额',
    notaxprice      double(12,2)    not null DEFAULT '0.00' COMMENT '不含税价',
    totalfinish     int(10)         NOT NULL DEFAULT '0' COMMENT '已入库数量',
    totalway        int(10)         NOT NULL DEFAULT '0' COMMENT '在途数量',
    totalrefund     int(10)         NOT NULL DEFAULT '0' COMMENT '退货数量',
    statusreceipt   enum('Y','N','P') NOT NULL DEFAULT 'N' COMMENT '收货状态: N 未收货, P 部分收货, Y 完成收货',
    statusrefund    enum('Y','N','P') NOT NULL DEFAULT 'N' COMMENT '退货状态: N 未退货, P 部分退货, Y 全部退货',
    statusaudit     enum('Y','N','R','F') NOT NULL DEFAULT 'N' COMMENT '审核状态: Y 通过审核, R 待修改, N 待审核, F 拒绝',
    status          tinyint         NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '采购 - 供应商关联表';
--/*}}}*/

-- --采购 - 商品明细关联表---/*{{{*/
DROP TABLE IF EXISTS purchaseproduct;
CREATE TABLE purchaseproduct (
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    purchaseid      int(10)         NOT NULL DEFAULT '0' COMMENT '采购单ID',
    productid       int(10)         NOT NULL DEFAULT '0' COMMENT '商品ID',
    storeid         int(10)         NOT NULL DEFAULT '0' COMMENT '到货库房ID',
    partsid         int(10)         NOT NULL DEFAULT '0' COMMENT '单位ID',
    total           mediumint       NOT NULL DEFAULT '0' COMMENT '商品数量',
    totalfinish     mediumint       NOT NULL DEFAULT '0' COMMENT '已入库数量',
    totalway        mediumint       NOT NULL DEFAULT '0' COMMENT '在途数量',
    totalrefund     mediumint       NOT NULL DEFAULT '0' COMMENT '退货数量',
    price           double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '采购单价',
    taxprice        double(12,2)    not null DEFAULT '0.00' COMMENT '含税价',
    taxrate         tinyint         not null COMMENT '税率',
    tax             double(12,2)    not null DEFAULT '0.00' COMMENT '税额',
    notaxprice      double(12,2)    not null DEFAULT '0.00' COMMENT '不含税价',
    -- comment         text            NOT NULL COMMENT '备注',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '状态：Y正常, N删除',
    status          tinyint         NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '采购 - 商品明细关联表';
--/*}}}*/

-- --采购 - 银行关联表---/*{{{*/
DROP TABLE IF EXISTS purchasefinance;
CREATE TABLE purchasefinance (
    id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    purchaseid      int(11)         NOT NULL DEFAULT '0' COMMENT '采购单ID',
    supplierid      int(11)         NOT NULL COMMENT '供应商id',
    bankid          int(11)         NOT NULL DEFAULT '0' COMMENT '付款银行ID',
    paymenttotal    float(12,2)     NOT NULL DEFAULT '0.00' COMMENT '采购总价(含税)',
    paymentalready  float(12,2)     NOT NULL DEFAULT '0.00' COMMENT '已付金额',
    paymentremain   float(12,2)     NOT NULL COMMENT '欠款尾款',
    paymentreturn   float(12,2)     NOT NULL DEFAULT '0.00' COMMENT '供货商欠款',
    status          enum('Y','N','D') NOT NULL DEFAULT 'N' COMMENT '付款状态: N 未付款, D 部分付款, Y 完成付款'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '采购 - 银行关联表';
--/*}}}*/

-- 问答系统 - 平台信息--/*{{{*/
DROP TABLE IF EXISTS platform;
CREATE TABLE platform (
    id              mediumint       NOT NULL PRIMARY KEY COMMENT '自然序号',
    name            varchar(40)     NOT NULL COMMENT '平台名称',
    body            text            NOT NULL COMMENT '备注',
    createtime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
    updatetime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '问答系统 - 平台信息';
--/*}}}*/

-- 问答系统 - 问题答案信息---/*{{{*/
DROP TABLE IF EXISTS problemanswer;
CREATE TABLE problemanswer (
    id              int             NOT NULL PRIMARY KEY COMMENT '自然序号',
    platformid      mediumint       NOT NULL COMMENT '平台ID',
    problem         text            NOT NULL COMMENT '问题',
    answer          text            NOT NULL COMMENT '答案',
    createtime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
    updatetime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
    isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT'问答系统 - 问题答案信息';
--/*}}}*/
-- 快递公司 - 快递公司信息---/*{{{*/
DROP TABLE IF EXISTS expresscompanyinfo;
CREATE TABLE expresscompanyinfo (
    id              bigint(20)      NOT NULL PRIMARY KEY,
    name            varchar(40)     NOT NULL COMMENT '自定义快递公司名称',
    payment         double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '应付款',
    fee             double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '工本费',
    stateid         int(11)          NOT NULL DEFAULT '0' COMMENT '所在省份ID',
    cityid          int(11)          NOT NULL DEFAULT '0' COMMENT '所在城市ID',
    districtid      int(11)         NOT NULL DEFAULT '0' COMMENT '所在地区ID',
    address         varchar(60)     NOT NULL COMMENT '详细地址',
    postcode        varchar(6)      NOT NULL COMMENT '邮编',
    contactname     varchar(20)     NOT NULL COMMENT '联系人',
    telphone        varchar(20)     NOT NULL COMMENT '固定电话',
    mobile          varchar(20)     NOT NULL COMMENT '手机',
    body            text            NOT NULL COMMENT '备注',
    createtime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
    updatetime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
    status          enum('Y','S','D') NOT NULL DEFAULT 'Y' COMMENT '是否停用: Y正常, S停用, D删除'
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='快递公司表';
--/*}}}*/

-- 快递公司运费信息 ---/*{{{*/
DROP TABLE IF EXISTS expressprice;
CREATE TABLE expressprice (
    id              int(11)         NOT NULL PRIMARY KEY,
    expressid       int(11)         NOT NULL COMMENT '快递公司ID',
    arealist        text            NOT NULL COMMENT '派送地区',
    storeid         varchar(100)    NOT NULL COMMENT '仓库(发货地)',
    firstweight1    float(12,2)     NULL COMMENT '首重1',
    firstweight2    float(12,2)     NULL COMMENT '首重2',
    firstweight3    float(12,2)     NULL COMMENT '首重3',
    firstweight4    float(12,2)     NULL COMMENT '首重4',
    firstweight5    float(12,2)     NULL COMMENT '首重5',
    firstprice1     float(12,2)     NULL COMMENT '首重1费用',
    firstprice2     float(12,2)     NULL COMMENT '首重2费用',
    firstprice3     float(12,2)     NULL COMMENT '首重3费用',
    firstprice4     float(12,2)     NULL COMMENT '首重4费用',
    firstprice5     float(12,2)     NULL COMMENT '首重5费用',
    weightincrease  float(12,2)     NULL COMMENT '续重',
    priceincrease   float(12,2)     NULL COMMENT '续重费用',
    createtime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
    updatetime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间'
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='快递公司 - 快递费用';
--/*}}}*/

-- 财务科目类型表 ---/*{{{*/
DROP TABLE IF EXISTS accounttype;
CREATE TABLE accounttype (
    id              int         NOT NULL PRIMARY KEY,
    typename        varchar(200)    NOT NULL COMMENT '科目类型名称',
    remark          varchar(500)    NOT NULL COMMENT '备注',
    status          enum('Y','D')   NOT NULL DEFAULT 'Y' COMMENT '状态：Y正常, D删除',
    createtime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
    updatetime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间'
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='财务科目类型表';
--/*}}}*/

-- 财务科目类别表 ---/*{{{*/
DROP TABLE IF EXISTS accountcategory;
CREATE TABLE accountcategory (
    id              int             NOT NULL PRIMARY KEY,
    acctypeid       int             NOT NULL COMMENT '财务科目类型ID',
    goryname        varchar(200)    NOT NULL COMMENT '科目类别名称',
    remark          varchar(500)    NOT NULL COMMENT '备注',
    status          enum('Y','D')   NOT NULL DEFAULT 'Y' COMMENT '状态：Y正常, D删除',
    createtime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
    updatetime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间'
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='财务科目类别表';
--/*}}}*/

-- 财务科目表 ---/*{{{*/
DROP TABLE IF EXISTS financialaccount;
CREATE TABLE financialaccount (
    id              int             NOT NULL PRIMARY KEY,
    code            varchar(50)     NOT NULL COMMENT '编码',
    name            varchar(200)    NOT NULL COMMENT '科目名称',
    remark          varchar(500)    NOT NULL COMMENT '备注',
    parent          int             NOT NULL DEFAULT  0 COMMENT '上级id',
    acctypeid       int             NOT NULL COMMENT '财务科目类型ID',
    accgoryid       int             NOT NULL COMMENT '财务科目类别ID',
    balance         enum('J','D')   NOT NULL DEFAULT 'J' COMMENT '状态：J借, D贷',
    status          enum('Y','D')   NOT NULL DEFAULT 'Y' COMMENT '状态：Y正常, D删除',
    createtime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
    updatetime      datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间'
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='财务科目表';
--/*}}}*/

--快递单模板基本信息 ---/*{{{*/
DROP TABLE IF EXISTS expresstemplateinfo;
CREATE TABLE expresstemplateinfo (
  id int NOT NULL PRIMARY KEY COMMENT '模板编号',
  pressid int NOT NULL DEFAULT '0' COMMENT '快递公司ID',
  name varchar(40) NOT NULL COMMENT '模板名称',
  image varchar(200) NOT NULL COMMENT '快递单背景图片',
  paperwidth int NOT NULL DEFAULT '0' COMMENT '纸张宽度 (毫米)',
  paperheight int NOT NULL DEFAULT '0' COMMENT '纸张高度 (毫米)',
  paperleft int NOT NULL DEFAULT '0' COMMENT '水平偏移 (像素)',
  papertop int NOT NULL DEFAULT '0' COMMENT '垂直偏移 (像素)',
  status enum('Y','D') NOT NULL DEFAULT 'Y' COMMENT '状态：Y正常, D删除',
  createtime datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  updatetime datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间'
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='快递单模板 - 基本信息';
--/*}}}*/

-- 快递单模板 - 栏目定义---/*{{{*/
DROP TABLE IF EXISTS expresstemplateitem;
CREATE TABLE expresstemplateitem (
  id int NOT NULL PRIMARY KEY COMMENT '栏目编号',
  name varchar(40) NOT NULL COMMENT '栏目名称',
  createtime datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  updatetime datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间'
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='快递单模板 - 栏目定义';
--/*}}}*/

-- 快递单模板 - 快递单栏目位置---/*{{{*/
DROP TABLE IF EXISTS expresstemplateposition;
CREATE TABLE expresstemplateposition (
  id                int             NOT NULL PRIMARY KEY,
  templateid        int             NOT NULL DEFAULT '0' COMMENT '快递单模板ID',
  itemid            int             NOT NULL DEFAULT '0' COMMENT '栏目ID',
  itemwidth         int             NOT NULL DEFAULT '0' COMMENT '控件宽度',
  itemheight        int             NOT NULL DEFAULT '0' COMMENT '控件高度',
  itemleft          int             NOT NULL DEFAULT '0' COMMENT '控件左边距',
  itemtop           int             NOT NULL DEFAULT '0' COMMENT '控件上边距',
  itemfontsize      int             NOT NULL DEFAULT '0' COMMENT '字体大小',
  createtime        datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  updatetime        datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间'
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='快递单模板 - 快递单栏目位置';
--/*}}}*/

-- 财务科目余额表---/*{{{*/
DROP TABLE IF EXISTS subjectbalance;
CREATE TABLE subjectbalance (
  id                int             NOT NULL PRIMARY KEY COMMENT '序列号ID',
  faccountid        int             NOT NULL COMMENT '财务科目表ID',
  initialpce        double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '期初余额',
  changepce         double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '发生金额',
  endingpce         double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '期末金额',
  createtime        datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  updatetime        datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间'
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='财务科目余额表';
--/*}}}*/

-- 帐号交易记录表---/*{{{*/
DROP TABLE IF EXISTS bankactaction;
CREATE TABLE bankactaction (
  id                int             NOT NULL PRIMARY KEY COMMENT '序列号ID',
  createtime        datetime        NOT NULL COMMENT '创建时间',
  updatetime        datetime        NOT NULL COMMENT '修改时间',
  bankid            int             NOT NULL COMMENT '银行帐号ID',
  staffid           int             NOT NULL DEFAULT '0' COMMENT '操作人',
  changepce         double(12,2)    NOT NULL COMMENT '发生金额',
  type              enum('I','D')   NOT NULL COMMENT '金额变化类型：I增, D减',
  purpose           varchar(300)    NOT NULL COMMENT '用途',
  endingpce         double(12,2)    NOT NULL COMMENT '金额'
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='财务科目余额表';
--/*}}}*/

-- 采购 - 采购资金流水记录表---/*{{{*/
DROP TABLE IF EXISTS moneyrecode;
CREATE TABLE moneyrecode (
  id                int             NOT NULL PRIMARY KEY COMMENT '自然序号',
  createtime        datetime        NOT NULL COMMENT '创建时间',
  updatetime        datetime        NOT NULL COMMENT '修改时间',
  infoid            int             NOT NULL COMMENT '采购单ID',
  bankid            int             NOT NULL COMMENT '银行账户ID',
  faccountid        int             NOT NULL COMMENT '财务科目表ID',
  tradesum          float(12,2)     NOT NULL DEFAULT '0.00' COMMENT '交易金额',
  paymentalready    float(12,2)     NOT NULL DEFAULT '0.00' COMMENT '已付金额',
  paymentremain     float(12,2)     NOT NULL DEFAULT '0.00' COMMENT '欠款尾款',
  paymentreturn     float(12,2)     NOT NULL DEFAULT '0.00' COMMENT '供货商欠款',
  type              enum('Input','Output') NOT NULL COMMENT '单据类型: Input 收入单据, Output 支出单据',
  comment           text            NOT NULL COMMENT '备注',
  staffid           int             NOT NULL DEFAULT '0' COMMENT '操作人'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '采购 - 采购资金流水记录表';
--/*}}}*/

-- 采购 - 商品出入库单据---/*{{{*/
DROP TABLE IF EXISTS purchaseoibill;
CREATE TABLE purchaseoibill
(
  id              int(11)         NOT NULL PRIMARY KEY COMMENT '出入库单据ID',
  createtime      datetime        NOT NULL COMMENT '创建时间',
  updatetime      datetime        NOT NULL COMMENT '修改时间',
  supplierid      int             NOT NULL COMMENT '供应商ID',
  storeid         int             NOT NULL COMMENT '仓库ID',
  purchaseid      int             NOT NULL DEFAULT '0' COMMENT '对应采购单ID',
  total           int             NOT NULL COMMENT '出入库数量',
  price           double(12,2)    NOT NULL COMMENT '出入库总价',
  userid          int             NOT NULL COMMENT '操作人ID',
  companyid       int             NOT NULL COMMENT '所属公司ID',
  faccountid      int             NOT NULL COMMENT '财务科目表ID',
  storetype       enum('Input','Output') NOT NULL COMMENT '单据类型：入库，出库',
  isdelete enum('Y','N')          NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '采购 - 商品出入库单据';
--/*}}}*/

-- 采购 - 出入库单商品明细---/*{{{*/
DROP TABLE IF EXISTS gstorageinfo;
CREATE TABLE gstorageinfo (
  id            int               NOT NULL PRIMARY KEY COMMENT '自然序号',
  createtime    datetime          NOT NULL COMMENT '创建时间',
  updatetime    datetime          NOT NULL COMMENT '修改时间',
  infoid        int               NOT NULL DEFAULT '0' COMMENT '出入库单据ID',
  storeid       int               NOT NULL DEFAULT '0' COMMENT '仓库ID',
  areaid        int               NOT NULL DEFAULT '0' COMMENT '库区ID',
  shelvesid     int               NOT NULL DEFAULT '0' COMMENT '货架ID',
  locationid    int               NOT NULL DEFAULT '0' COMMENT '货位ID',
  productid     bigint            NOT NULL DEFAULT '0' COMMENT '商品ID',
  total         int               NOT NULL DEFAULT '0' COMMENT '数量',
  price         double(12,2)      NOT NULL DEFAULT '0.00' COMMENT '单价',
  payment       double(12,2)      NOT NULL DEFAULT '0.00' COMMENT '金额',
  body          varchar(300)      NOT NULL COMMENT '备注',
  status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='采购 - 出入库单商品明细';
--/*}}}*/

-- 生产--代工户信息表---/*{{{*/
DROP TABLE IF EXISTS processfactory;
CREATE TABLE processfactory (
id              int(11)         NOT NULL PRIMARY KEY COMMENT '代工户ID',
createtime      datetime        NOT NULL COMMENT '创建时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
name            varchar(32)     NOT NULL COMMENT '代工户名称',
number          varchar(32)     NOT NULL COMMENT '代工户编码',
website         varchar(128)    NOT NULL COMMENT '网站网址',
stateid         int(11)         NOT NULL DEFAULT '0' COMMENT '所在省份ID',
cityid          int(11)         NOT NULL DEFAULT '0' COMMENT '所在城市ID',
districtid      int(11)         NOT NULL DEFAULT '0' COMMENT '所在地区ID',
address         varchar(256)    NOT NULL COMMENT '详细地址',
postcode        varchar(6)      NOT NULL COMMENT '邮编',
mailbox         varchar(30)     NOT NULL COMMENT '邮箱',
contactname     varchar(20)     NOT NULL COMMENT '联系人',
phone           varchar(20)     NOT NULL COMMENT '固话',
mobile          varchar(20)     NOT NULL COMMENT '手机',
tax             varchar(20)     NOT NULL COMMENT '税号',
fax             varchar(20)     NOT NULL COMMENT '传真',
bankname        varchar(128)    NOT NULL COMMENT '开户银行',
banknumber      varchar(50)     NOT NULL COMMENT '银行账号',
balance         double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '结算金额',
comment         text            NOT NULL COMMENT '备注',
level           enum('P','A','E') NOT NULL COMMENT '代工户级别：P 主选代工户, A 备选代工户, E 淘汰代工户',
isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除：N正常, Y已删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '生产--代工户信息表';
--/*}}}*/

-- 采购 - 采购单票据主表---/*{{{*/
DROP TABLE IF EXISTS purchasebillmain;
CREATE TABLE purchasebillmain (
    id              int             NOT NULL PRIMARY KEY COMMENT '采购单票据ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    stamptype       enum('S','G')   NOT NULL DEFAULT 'S' COMMENT '票种：S专票, G普票',
    purchaseid      varchar(800)    NOT NULL DEFAULT '0' COMMENT '对应采购单编号',
    taxprice        double(12,2)    NOT NULL COMMENT '金额(含税)',
    taxrate         tinyint         NOT NULL COMMENT '税率',
    taxtotal        double(12,2)    NOT NULL COMMENT '税额',
    taxedprice      double(12,2)    NOT NULL COMMENT '金额(不含税)',
    bankid          int             NOT NULL COMMENT '银行帐号ID',
    staffid         int(11)         NOT NULL COMMENT '操作人',
    comment         varchar(320)    NOT NULL COMMENT '备注',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '采购 - 采购单票据主表';
--/*}}}*/

-- 采购 - 采购单票据附表---/*{{{*/
DROP TABLE IF EXISTS purchasebillattach;
CREATE TABLE purchasebillattach (
    id              int             NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    purchasebillid  int             NOT NULL COMMENT '采购单票据表ID',
    faccountid      int             NOT NULL COMMENT '财务科目表ID',
    price           double(12,2)    NOT NULL COMMENT '金额',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '采购 - 采购单票据附表';
--/*}}}*/

-- 采购 - 采购运费记录表---/*{{{*/
DROP TABLE IF EXISTS purchasefreightinfo;
CREATE TABLE purchasefreightinfo (
    id              int             NOT NULL PRIMARY KEY COMMENT '运费单编号ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    staffid         int             NOT NULL COMMENT '操作人',
    bankid          int             NOT NULL COMMENT '银行帐号ID',
    purchaseid      varchar(800)    NOT NULL COMMENT '对应采购单编号',
    taxprice        double(12,2)    NOT NULL COMMENT '金额(含税)',
    taxrate         tinyint         NOT NULL COMMENT '税率',
    taxtotal        double(12,2)    NOT NULL COMMENT '税额',
    taxedprice      double(12,2)    NOT NULL COMMENT '金额(不含税)',
    shippingcpy     varchar(50)     NOT NULL COMMENT '托运公司',
    waybillnbr      varchar(40)     NOT NULL COMMENT '运单号码',
    comment         varchar(320)    NOT NULL COMMENT '备注',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '采购 - 采购运费记录表';
--/*}}}*/

-- 采购 - 运费-发票-财务关联表---/*{{{*/
DROP TABLE IF EXISTS freinvoicefinrelated;
CREATE TABLE freinvoicefinrelated (
    id              int             NOT NULL PRIMARY KEY COMMENT '自然序号',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    type            enum('F','P')   NOT NULL COMMENT '种类: F 运费单编号ID, P 运费发票单编号ID',
    infoid          int             NOT NULL COMMENT '运费单编号ID, 运费发票单编号ID',
    faccountid      int             NOT NULL COMMENT '财务科目表ID',
    direction       enum('B','I')   NOT NULL COMMENT '借贷方向: B 借, I 贷',
    price           double(12,2)    NOT NULL COMMENT '金额',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '采购 - 运费-发票-财务关联表';
--/*}}}*/

-- 采购 - 采购运费发票记录表---/*{{{*/
DROP TABLE IF EXISTS purchasefreinvoiceinfo;
CREATE TABLE purchasefreinvoiceinfo (
    id              int             NOT NULL PRIMARY KEY COMMENT '运费发票单编号ID',
    createtime      datetime        NOT NULL COMMENT '创建时间',
    updatetime      datetime        NOT NULL COMMENT '修改时间',
    staffid         int             NOT NULL COMMENT '操作人',
    purchaseid      varchar(800)    NOT NULL COMMENT '对应运费单编号',
    taxprice        double(12,2)    NOT NULL COMMENT '金额(含税)',
    taxrate         tinyint         NOT NULL COMMENT '税率',
    taxtotal        double(12,2)    NOT NULL COMMENT '税额',
    taxedprice      double(12,2)    NOT NULL COMMENT '金额(不含税)',
    commnet         varchar(320)    NOT NULL COMMENT '备注',
    status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '采购 - 采购运费发票记录表';
--/*}}}*/

-- 生产 - 生产单主表---/*{{{*/
DROP TABLE IF EXISTS manufactory;
CREATE TABLE manufactory (
id              int(11)         NOT NULL PRIMARY KEY COMMENT '生产单ID',
createtime      datetime        NOT NULL COMMENT '创建时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
number          varchar(40)     NOT NULL COMMENT '生产单编号',
storeid         int(10)         NOT NULL COMMENT '入库仓库ID',
productid       int(10)         NOT NULL COMMENT '商品ID',
total           int(10)         NOT NULL DEFAULT '0' COMMENT '生产数量',
totalfinish     int(10)         NOT NULL DEFAULT '0' COMMENT '已入库数量',
totalway        int(10)         NOT NULL DEFAULT '0' COMMENT '在途数量',
totalrefund     int(10)         NOT NULL DEFAULT '0' COMMENT '返工出库数量',
staffid         int(10)         NOT NULL DEFAULT '0' COMMENT '操作人',
brief           varchar(256)    NOT NULL COMMENT '生产单摘要',
actiondate      datetime        NOT NULL COMMENT '下单时间',
comment         text            NOT NULL COMMENT '备注',
prostatus       enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '生产状态: N 未生产, Y 已生产',
statusreceipt   enum('Y','N','P') NOT NULL DEFAULT 'N' COMMENT '收货状态: N 未收货, P 部分收货, Y 完成收货',
statusrefund    enum('Y','N','P') NOT NULL DEFAULT 'N' COMMENT '退货状态: N 未返工, P 部分返工, Y 全部返工',
statusaudit     enum('Y','N','R','F') NOT NULL DEFAULT 'N' COMMENT '审核状态: Y 通过审核, R 待修改, N 待审核, F拒接'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '生产 - 生产单主表';
--/*}}}*/

-- 生产 - 代工户明细关联表---/*{{{*/
DROP TABLE IF EXISTS processmanufactory;
CREATE TABLE processmanufactory (
id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
createtime      datetime        NOT NULL COMMENT '创建时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
productinfoid   int(10)         NOT NULL COMMENT '生产单ID',
profactoryid    int(10)         NOT NULL COMMENT '代工户ID',
storeid         int(10)         NOT NULL COMMENT '入库仓库ID',
total           int(10)         NOT NULL DEFAULT '0' COMMENT '生产数量',
totalfinish     int(10)         NOT NULL DEFAULT '0' COMMENT '已入库数量',
totalway        int(10)         NOT NULL DEFAULT '0' COMMENT '在途数量',
totalrefund     int(10)         NOT NULL DEFAULT '0' COMMENT '返工出库数量',
comment         text            NOT NULL COMMENT '备注',
status          enum('Y','D')   NOT NULL DEFAULT 'Y' COMMENT '状态: Y正常, D已删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '生产 - 代工户明细关联表';
--/*}}}*/

-- 生产 - 代工户商品出入库明细---/*{{{*/
DROP TABLE IF EXISTS fprochange;
CREATE TABLE fprochange (
id            int(11)           NOT NULL PRIMARY KEY COMMENT '自然序号',
createtime    datetime          NOT NULL COMMENT '创建时间',
updatetime    datetime          NOT NULL COMMENT '修改时间',
infoid        int(11)           NOT NULL COMMENT '出入库单据编号ID',
profactoryid  int(10)           NOT NULL COMMENT '代工户ID',
productid     bigint(20)        NOT NULL DEFAULT '0' COMMENT '商品ID',
total         int(10)           NOT NULL DEFAULT '0' COMMENT '数量',
comment       varchar(400)      NOT NULL COMMENT '备注',
isdelete      enum('Y','N')     NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT='生产 - 代工户商品出入库明细';
--/*}}}*/

-- 生产 - 代工户商品出入库单据---/*{{{*/
DROP TABLE IF EXISTS fprobill;
CREATE TABLE fprobill(
id              int(11)         NOT NULL PRIMARY KEY COMMENT '出入库单据编号ID',
createtime      datetime        NOT NULL COMMENT '创建时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
storeid         int(10)         NOT NULL COMMENT '仓库ID',
productinfoid   int(10)         NOT NULL COMMENT '对应生产单ID',
productid       bigint(20)      NOT NULL DEFAULT '0' COMMENT '商品ID',
total           int(10)         NOT NULL COMMENT '出入库数量',
userid          int(10)         NOT NULL COMMENT '操作人ID',
actiontime      datetime        NOT NULL COMMENT '操作时间',
storetype       enum('I','O')   NOT NULL COMMENT '单据类型：I 入库，O 出库',
isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '生产 - 代工户商品出入库单据';
--/*}}}*/

-- 代工库手动减库---/*{{{*/
DROP TABLE IF EXISTS fstoredes;
CREATE TABLE fstoredes(
id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
createtime      datetime        NOT NULL COMMENT '创建时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
productid       bigint          NOT NULL COMMENT '商品ID',
profactoryid    int(11)         NOT NULL COMMENT '代工库ID',
staffid         int(11)         NOT NULL DEFAULT '0' COMMENT '操作人',
actiontime      datetime        NOT NULL COMMENT '操作时间',
total           mediumint       NOT NULL DEFAULT '0' COMMENT '数量',
comment         text            NOT NULL COMMENT '备注',
isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '代工库手动减库';
--/*}}}*/

-- 代工库实时原料明细数量---/*{{{*/
DROP TABLE IF EXISTS fstoresync;
CREATE TABLE fstoresync(
id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
createtime      datetime        NOT NULL COMMENT '创建时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
productid       bigint          NOT NULL COMMENT '商品ID',
profactoryid    int(11)         NOT NULL COMMENT '代工库ID',
total           mediumint       NOT NULL DEFAULT '0' COMMENT '数量',
isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '代工库实时原料明细数量';
--/*}}}*/

-- 生产公用关联财务科目表---/*{{{*/
DROP TABLE IF EXISTS fprofinance;
CREATE TABLE fprofinance (
id              int             NOT NULL PRIMARY KEY COMMENT '自然序号',
createtime      datetime        NOT NULL COMMENT '创建时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
type            enum('S','B','F','P')   NOT NULL COMMENT '种类: S 代工户结算记录编码ID,B 日常开票记录编号ID,F 生产运费单编号ID,P 生产运费发票单编号ID',
infoid          int             NOT NULL COMMENT '代工户结算记录编码ID,日常开票记录编号ID,生产运费单编号ID,生产运费发票单编号ID',
faccountid      int             NOT NULL COMMENT '财务科目表ID',
direction       enum('B','I')   NOT NULL COMMENT '借贷方向: B 借, I 贷',
price           double(12,2)    NOT NULL COMMENT '金额',
isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '生产公用关联财务科目表';
--/*}}}*/

-- 生产 - 手动调拨原料---/*{{{*/
DROP TABLE IF EXISTS allocateraw;
CREATE TABLE allocateraw
(
id              int(11)         NOT NULL PRIMARY KEY COMMENT '自然序号',
createtime      datetime        NOT NULL COMMENT '调拨时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
productid       bigint          NOT NULL DEFAULT '0' COMMENT '商品ID',
moveoutid       int(11)         NOT NULL DEFAULT '0' COMMENT '调出库ID',
profactoryid    int(11)         NOT NULL DEFAULT '0' COMMENT '调入库(代工库)ID',
staffid         int(11)         NOT NULL DEFAULT '0' COMMENT '操作人',
total           mediumint       NOT NULL DEFAULT '0' COMMENT '数量',
comment         text            NOT NULL COMMENT '备注',
isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '生产 - 手动调拨原料';
--/*}}}*/

-- 生产 - 日常开票记录编号---/*{{{*/
DROP TABLE IF EXISTS makinvoicelog;
CREATE TABLE makinvoicelog (
id              int             NOT NULL PRIMARY KEY COMMENT '日常开票记录ID',
createtime      datetime        NOT NULL COMMENT '创建时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
actiontime      datetime        NOT NULL COMMENT '操作时间',
stamptype       enum('S','G')   NOT NULL DEFAULT 'S' COMMENT '票种：S专票, G普票',
productinfoid   varchar(800)    NOT NULL DEFAULT '0' COMMENT '对应生产单编号',
taxprice        double(12,2)    NOT NULL COMMENT '金额(含税)',
taxrate         tinyint         NOT NULL COMMENT '税率',
taxtotal        double(12,2)    NOT NULL COMMENT '税额',
taxedprice      double(12,2)    NOT NULL COMMENT '金额(不含税)',
bankid          int             NOT NULL COMMENT '银行帐号ID',
staffid         int(11)         NOT NULL COMMENT '操作人',
comment         varchar(320)    NOT NULL COMMENT '备注',
isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '生产 - 日常开票记录编号';
--/*}}}*/

-- 生产 - 代工户结算记录---/*{{{*/
DROP TABLE IF EXISTS fprosettle;
CREATE TABLE fprosettle
(
id              int(11)         NOT NULL PRIMARY KEY COMMENT '代工户结算记录编码',
createtime      datetime        NOT NULL COMMENT '调拨时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
productinfoid   varchar(800)    NOT NULL COMMENT '对应生产单编号',
bankid          int(10)         NOT NULL COMMENT '交易银行帐号ID',
profactoryid    int(11)         NOT NULL COMMENT '代工库ID',
taxprice        double(12,2)    NOT NULL COMMENT '金额(含税)',
taxrate         tinyint         NOT NULL COMMENT '税率',
taxtotal        double(12,2)    NOT NULL COMMENT '税额',
taxedprice      double(12,2)    NOT NULL COMMENT '金额(不含税)',
comment         varchar(320)    NOT NULL COMMENT '备注',
staffid         int(11)         NOT NULL DEFAULT '0' COMMENT '操作人',
actiontime      datetime        NOT NULL COMMENT '操作时间',
isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE = InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '生产 - 代工户结算记录';
--/*}}}*/

-- 生产 - 生产运费发票记录表---/*{{{*/
DROP TABLE IF EXISTS pfreightbill;
CREATE TABLE pfreightbill (
id              int             NOT NULL PRIMARY KEY COMMENT '生产运费发票单编号ID',
createtime      datetime        NOT NULL COMMENT '创建时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
actiontime      datetime        NOT NULL COMMENT '操作时间',
staffid         int             NOT NULL COMMENT '操作人',
stamptype       enum('S','G')   NOT NULL DEFAULT 'S' COMMENT '票种：S专票, G普票',
purchaseid      varchar(800)    NOT NULL COMMENT '对应生产运费单编号ID',
taxprice        double(12,2)    NOT NULL COMMENT '金额(含税)',
taxrate         tinyint         NOT NULL COMMENT '税率',
taxtotal        double(12,2)    NOT NULL COMMENT '税额',
taxedprice      double(12,2)    NOT NULL COMMENT '金额(不含税)',
comment         varchar(320)    NOT NULL COMMENT '备注',
status          tinyint(4)      NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '生产 - 生产运费发票记录表';
--/*}}}*/

-- 生产 - 生产运费发票记录表---/*{{{*/
DROP TABLE IF EXISTS purfreightlog;
CREATE TABLE purfreightlog (
id              int             NOT NULL PRIMARY KEY COMMENT '生产运费单编号ID',
createtime      datetime        NOT NULL COMMENT '创建时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
actiontime      datetime        NOT NULL COMMENT '操作时间',
staffid         int             NOT NULL COMMENT '操作人',
bankid          int             NOT NULL COMMENT '银行帐号ID',
productinfoid   varchar(800)    NOT NULL COMMENT '对应采购单编号',
taxprice        double(12,2)    NOT NULL COMMENT '金额(含税)',
taxrate         tinyint         NOT NULL COMMENT '税率',
taxtotal        double(12,2)    NOT NULL COMMENT '税额',
taxedprice      double(12,2)    NOT NULL COMMENT '金额(不含税)',
shippingcpy     varchar(50)     NOT NULL COMMENT '托运公司',
waybillnbr      varchar(40)     NOT NULL COMMENT '运单号码',
comment         varchar(320)    NOT NULL COMMENT '备注',
isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '生产 - 采购运费记录表';
--/*}}}*/

-- 订单 -异常定义---/*{{{*/
DROP TABLE IF EXISTS orderunusual;
CREATE TABLE orderunusual (
  id            int             NOT NULL PRIMARY KEY COMMENT '序列号',
  createtime    datetime        NOT NULL COMMENT '创建时间',
  updatetime    datetime        NOT NULL COMMENT '修改时间',
  name          varchar(50)     NOT NULL DEFAULT '' COMMENT '异常名称',
  isdelete      enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 -异常定义';
--/*}}}*/

-- 订单 -分类定义---/*{{{*/
DROP TABLE IF EXISTS ordercategory;
CREATE TABLE ordercategory (
  id            int             NOT NULL PRIMARY KEY COMMENT '分类ID',
  createtime    datetime        NOT NULL COMMENT '创建时间',
  updatetime    datetime        NOT NULL COMMENT '修改时间',
  name          varchar(50)     NOT NULL DEFAULT '' COMMENT '分类名称',
  remark        varchar(500)    NOT NULL DEFAULT '' COMMENT '备注',
  isdelete      enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 -分类定义';
--/*}}}*/

-- 物流公司表---/*{{{*/
DROP TABLE IF EXISTS  transportinfo;
CREATE TABLE transportinfo (
id              bigint(20)      NOT NULL PRIMARY KEY COMMENT '物流公司ID',
createtime      datetime        NOT NULL COMMENT '创建时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
name            varchar(40)     NOT NULL COMMENT '物流公司名称',
payment         double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '期初应付款',
stateid         int(11)         NOT NULL DEFAULT '0' COMMENT '所在省份ID',
cityid          int(11)         NOT NULL DEFAULT '0' COMMENT '所在城市ID',
districtid      int(11)         NOT NULL DEFAULT '0' COMMENT '所在地区ID',
address         varchar(60)     NOT NULL COMMENT '详细地址',
postcode        varchar(6)      NOT NULL COMMENT '邮编',
contactname     varchar(20)     NOT NULL COMMENT '联系人',
telphone        varchar(20)     NOT NULL COMMENT '固定电话',
mobile          varchar(20)     NOT NULL COMMENT '手机',
comment         text            NOT NULL COMMENT '备注',
isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '物流公司表';
--/*}}}*/

-- 订单-客户信息---/*{{{*/
DROP TABLE IF EXISTS  customerinfo;
CREATE TABLE customerinfo (
id              int             NOT NULL PRIMARY KEY COMMENT '客户ID',
createtime      datetime        NOT NULL COMMENT '创建时间',
updatetime      datetime        NOT NULL COMMENT '修改时间',
name            varchar(40)     NOT NULL DEFAULT '' COMMENT '客户名称',
nick            varchar(40)     NOT NULL DEFAULT '' COMMENT '客户昵称',
type            enum('P','E')   NOT NULL DEFAULT 'P' COMMENT '类型：P个人, E企业',
payment         double(12,2)    NOT NULL DEFAULT '0.00' COMMENT '期初结算金额',
mobile          varchar(20)     NOT NULL DEFAULT '' COMMENT '手机',
telphone        varchar(20)     NOT NULL DEFAULT '' COMMENT '固定电话',
companyname     varchar(200)    NOT NULL DEFAULT '' COMMENT '公司名称',
postcode        varchar(6)      NOT NULL DEFAULT '' COMMENT '邮编',
mailbox         varchar(20)     NOT NULL DEFAULT '' COMMENT '邮箱',
qq              varchar(20)     NOT NULL DEFAULT '' COMMENT 'QQ号码',
stateid         int(11)         NOT NULL DEFAULT '0' COMMENT '所在省份ID',
cityid          int(11)         NOT NULL DEFAULT '0' COMMENT '所在城市ID',
districtid      int(11)         NOT NULL DEFAULT '0' COMMENT '所在地区ID',
address         varchar(60)     NOT NULL DEFAULT '' COMMENT '详细地址',
comment         text            NOT NULL DEFAULT '' COMMENT '备注',
isdelete        enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单-客户信息';
--/*}}}*/

-- 系统 - 店铺名称---/*{{{*/
DROP TABLE IF EXISTS systemshop;
CREATE TABLE systemshop (
  id            int(11)         NOT NULL PRIMARY KEY COMMENT '店铺ID',
  createtime    datetime        NOT NULL COMMENT '创建时间',
  updatetime    datetime        NOT NULL COMMENT '修改时间',
  name          varchar(40)     NOT NULL COMMENT '店铺名称',
  comment       varchar(500)    NOT NULL COMMENT '备注',
  isdelete      enum('N','Y')   NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '系统 - 店铺名称';
--/*}}}*/

-- 订单 - 基本信息---/*{{{*/
DROP TABLE IF EXISTS  orderinfo;
CREATE TABLE orderinfo (
  id                int             NOT NULL PRIMARY KEY COMMENT '订单ID',
  createtime        datetime        NOT NULL COMMENT '创建时间',
  updatetime        datetime        NOT NULL COMMENT '修改时间',
  orderdate         datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '下单时间',
  afterdate         datetime        NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '售后退款日期',
  onlineid          varchar(20)     NOT NULL DEFAULT '' COMMENT '线上订单ID',
  categoryid        int             NOT NULL COMMENT '订单分类ID',
  channelid         int             NOT NULL COMMENT '购买渠道ID',
  companyid         int             NOT NULL COMMENT '店铺名称ID',
  storeid           int             NOT NULL COMMENT '发货仓库ID',
  customerid        int             NOT NULL COMMENT '客户ID',
  unusualid         int(11)         NOT NULL DEFAULT '0' COMMENT '异常ID',
  relatedid         varchar(100)    NOT NULL DEFAULT '' COMMENT '关联订单ID',
  serviceid         int             NOT NULL COMMENT '客服ID',
  isreceive         enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '代收贷款：Y是, N否',
  isbill            enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '发票收据：Y有, N无',
  deliversta        enum('N','E','P','A')   NOT NULL DEFAULT 'N' COMMENT '发货状态：N：无需发货, E:未发货, P:部分发货, A:完成发货',
  desstoresta       enum('N','H','A','B')   NOT NULL DEFAULT 'B' COMMENT '减库状态：N:未减库, H:手动减库, A:自动减库,B:无需减库',
  billtype          enum('Y','N','X') NOT NULL DEFAULT 'N' COMMENT '票种：N无,Y专票,X普票',
  comment           varchar(100)    NOT NULL DEFAULT '0' COMMENT '订单备注',
  isdelete          enum('Y','N')   NOT NULL DEFAULT 'N' COMMENT '是否删除',
  ypayment          float(12,2)     NOT NULL DEFAULT '0.00' COMMENT '应付金额',
  discount          float(12,2)     NOT NULL DEFAULT '0.00' COMMENT '优惠',
  version           int(11)         NOT NULL DEFAULT '0' COMMENT '版本号',
  splittimes        int(11)         NOT NULL DEFAULT '0' COMMENT '拆单次数',
  custimes          int(11)         NOT NULL DEFAULT '0' COMMENT '售后次数',
  type              enum('Y','N')   NOT NULL COMMENT '订单级别：Y主订单, N子订单',
  cusmsg            varchar(500)    NOT NULL DEFAULT '' COMMENT '客户留言',
  orstatus          enum('N','P','T','C','F','Y') NOT NULL DEFAULT 'N' COMMENT '订单状态: N 未审核,P:打单配货(已审核), T:条码验货, C:称重计费, F:扫单发货, Y:已发货'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 - 基本信息';
--/*}}}*/

-- 订单  -主子订单关联表---/*{{{*/
DROP TABLE IF EXISTS orderfterson;
CREATE TABLE orderfterson (
  id            int                 NOT NULL PRIMARY KEY COMMENT '自然序号',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  orderid       int                 NOT NULL COMMENT '子订单ID',
  porderid      int                 NOT NULL COMMENT '父订单ID',
  status        tinyint             NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 - 主子订单关联表';
--/*}}}*/

-- 订单 - 商品明细表---/*{{{*/
DROP TABLE IF EXISTS orderproduct;
CREATE TABLE orderproduct (
  id            int                 NOT NULL PRIMARY KEY COMMENT '自然序号',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  orderid       int                 NOT NULL COMMENT '订单ID(线下订单)',
  productid     int                 NOT NULL COMMENT '商品ID',
  total         int(11)             NOT NULL DEFAULT '0' COMMENT '商品数量',
  price         float(12,2)         NOT NULL DEFAULT '0.00' COMMENT '单价',
  discount      float(12,2)         NOT NULL DEFAULT '0.00' COMMENT '优惠',
  payment       float(12,2)         NOT NULL DEFAULT '0.00' COMMENT '应付金额',
  comment       text                NOT NULL DEFAULT '' COMMENT '备注',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 - 商品明细表';
--/*}}}*/

-- 订单 - 印刷详细---/*{{{*/
DROP TABLE IF EXISTS orderprint;
CREATE TABLE orderprint (
  id            int                 NOT NULL PRIMARY KEY COMMENT '印刷单ID',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  orderid       int                 NOT NULL COMMENT '订单ID',
  contents      varchar(200)        NOT NULL DEFAULT '' COMMENT '印刷内容',
  affirm        enum('Y','N','R')   NOT NULL DEFAULT 'N' COMMENT ' 印刷确认状况：N:无印刷 Y:已确认；R:待确认',
  complatesta   enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '印刷完成状况:Y：已完成；N：未完成',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 - 印刷详细';
--/*}}}*/

-- 订单 - 发货方式表---/*{{{*/
DROP TABLE IF EXISTS orderdeliver;
CREATE TABLE orderdeliver (
  id            int                 NOT NULL PRIMARY KEY COMMENT '订单发货ID',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  orderid       int                 NOT NULL COMMENT '订单ID(线下订单)',
  transportid   int                 NOT NULL DEFAULT '0' COMMENT '发货公司ID',
  waybill       varchar(256)        NOT NULL DEFAULT '' COMMENT '运单号(快递、物流)',
  freight       float(12,2)         NOT NULL DEFAULT '0.00' COMMENT '运费',
  realweight    float(12,2)         NOT NULL DEFAULT '0.00' COMMENT '实际重量',
  type          enum('K','W','N')   NOT NULL DEFAULT 'N' COMMENT '发货方式:K快递 W物流 N无',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 - 发货方式表';
--/*}}}*/

-- 订单 - 财务详细---/*{{{*/
DROP TABLE IF EXISTS orderfinance;
CREATE TABLE orderfinance (
  id            int                 NOT NULL PRIMARY KEY COMMENT '自然序号',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  orderid       int                 NOT NULL COMMENT '订单ID',
  financesubid  int(11)             NOT NULL DEFAULT '0' COMMENT '财务科目ID',
  bankid        int(11)             NOT NULL DEFAULT '0' COMMENT '银行账户ID',
  ypayment      float(12,2)         NOT NULL DEFAULT '0.00' COMMENT '应收金额',
  spayment      float(12,2)         NOT NULL DEFAULT '0.00' COMMENT '实收金额',
  qpayment      float(12,2)         NOT NULL DEFAULT '0.00' COMMENT '欠款尾数',
  status        enum('N','A','H','P') NOT NULL  DEFAULT 'N' COMMENT '财务入账: N:未入帐,A:自动入帐,H:手动入帐,P:部分入帐',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 - 财务详细';
--/*}}}*/

-- 订单 - 售后留言---/*{{{*/
DROP TABLE IF EXISTS aftersalemsg;
CREATE TABLE aftersalemsg (
  id            int(10)             NOT NULL PRIMARY KEY COMMENT '自然序号',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  orderid       int                 NOT NULL COMMENT '订单ID',
  staffid       int                 NOT NULL COMMENT '留言者ID',
  contents      varchar(320)        NOT NULL DEFAULT '' COMMENT '留言内容',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 - 售后留言';
--/*}}}*/

-- 订单 - 售后分类---/*{{{*/
DROP TABLE IF EXISTS aftersalecate;
CREATE TABLE aftersalecate (
  id            int(10)             NOT NULL PRIMARY KEY COMMENT '分类ID',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  catename      varchar(256)        NOT NULL COMMENT '分类名称',
  comment       varchar(320)        NOT NULL DEFAULT '' COMMENT '备注',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 - 售后分类';
--/*}}}*/

-- 订单 - 留言图片---/*{{{*/
DROP TABLE IF EXISTS ordermsgimg;
CREATE TABLE ordermsgimg (
  id            int(11)             NOT NULL PRIMARY KEY COMMENT '图片ID',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  orderid       int                 NOT NULL COMMENT '订单ID',
  filename      varchar(100)        NOT NULL COMMENT '图片名称',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 - 留言图片';
--/*}}}*/

-- 订单 - 操作记录---/*{{{*/
DROP TABLE IF EXISTS orderlog;
CREATE TABLE orderlog (
  id            int(11)             NOT NULL PRIMARY KEY COMMENT '日志ID',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  orderid       int                 NOT NULL COMMENT '订单ID',
  staffid       int                 NOT NULL COMMENT '操作人ID',
  operate       varchar(50)         NOT NULL DEFAULT '' COMMENT '操作内容',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 - 操作记录';
--/*}}}*/

-- 订单 - 拆分或合并---/*{{{*/
DROP TABLE IF EXISTS ordersplit;
CREATE TABLE ordersplit (
  id            int                 NOT NULL PRIMARY KEY COMMENT '自然序号',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  orderid       int                 NOT NULL COMMENT '源订单ID',
  targetid      int                 NOT NULL COMMENT '目标订单ID',
  splitstatus   enum('Split','Merge') NOT NULL COMMENT '拆分或合并',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单 - 拆分或合并';
--/*}}}*/

-- 订单售后服务 - 主表信息---/*{{{*/
DROP TABLE IF EXISTS ordersaleinfo;
CREATE TABLE ordersaleinfo (
  id            int(11)             NOT NULL PRIMARY KEY COMMENT '订单售后ID',
  orderid       int                 NOT NULL DEFAULT '0' COMMENT '订单ID',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  actiondate    datetime            NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '操作时间',
  backbankid    int(11)             NOT NULL DEFAULT '0' COMMENT '退款银行ID',
  cateid        int(11)             NOT NULL DEFAULT '0' COMMENT '售后服务分类ID',
  saletype      enum('Refunds','Return','Exchange','Repair','Delivery','Unknown') NOT NULL DEFAULT 'Unknown' COMMENT '售后类型：仅退款, 退货退款, 换货, 维修, 补发货, 其它未知',
  backpay       double(12,2)        NOT NULL DEFAULT '0.00' COMMENT '退款金额：正数退款给买家, 负数买家补差价',
  contents      varchar(300)        NOT NULL DEFAULT '' COMMENT '售后描述',
  isaccount     enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否记账',
  isstore       enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否入库',
  isorder       enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否下单',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除',
  version       int(11)             NOT NULL DEFAULT '0' COMMENT '版本号',
  staffid       int(11)             NOT NULL COMMENT '操作人(员工ID)',
  status        enum('Y','N','D')   NOT NULL DEFAULT 'N' COMMENT '状态: N待处理, Y已解决, D已关闭',
  backexpress   varchar(100)        NOT NULL DEFAULT '' COMMENT '退回发货公司名称',
  number        varchar(40)         NOT NULL DEFAULT '' COMMENT '退回单号',
  freight       enum('Customer','Company') NOT NULL DEFAULT 'Company' COMMENT '运费承担:Customer 客户, Company 本公司',
  backfee       double(12,2)        NOT NULL COMMENT '退回运费金额'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '订单售后服务 - 主表信息';
--/*}}}*/

-- 售后服务 - 退货明细表---/*{{{*/
DROP TABLE IF EXISTS asaleproduct;
CREATE TABLE asaleproduct (
  id            int(11)             NOT NULL PRIMARY KEY COMMENT '自然序号',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  actiondate    datetime            NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '操作时间',
  asaleid       int(11)             NOT NULL COMMENT '售后申请单ID',
  productid     int(11)             NOT NULL COMMENT '商品ID',
  staffid       int(11)             NOT NULL COMMENT '员工ID',
  total         int(11)             NOT NULL DEFAULT '0' COMMENT '商品数量',
  price         double(12,2)        NOT NULL COMMENT '商品单价',
  status        tinyint             NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '售后服务 - 退货明细表';
--/*}}}*/

-- 售后服务 - 入库单---/*{{{*/
DROP TABLE IF EXISTS asaleinstore;
CREATE TABLE asaleinstore (
  id            int(11)             NOT NULL PRIMARY KEY COMMENT '售后入库单ID',
  orderid       int(11)             NOT NULL DEFAULT '0' COMMENT '订单ID',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  actiondate    datetime            NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '操作时间',
  storeid       int(11)             NOT NULL COMMENT '仓库ID',
  productid     int(11)             NOT NULL COMMENT '商品ID',
  asaleid       int(11)             NOT NULL COMMENT '售后单ID',
  shopid        int(11)             NOT NULL DEFAULT '0' COMMENT '店铺ID',
  inedstore     int(11)             NOT NULL DEFAULT '0' COMMENT '已入库数量',
  outstore      int(11)             NOT NULL DEFAULT '0' COMMENT '未入库数量',
  comment       varchar(200)        NOT NULL DEFAULT '' COMMENT '备注',
  status        tinyint             NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '售后服务 - 入库单';
--/*}}}*/

-- 财务 - 退款记录---/*{{{*/
DROP TABLE IF EXISTS refundlogs;
CREATE TABLE refundlogs (
  id            int(11)             NOT NULL PRIMARY KEY COMMENT '自然序号',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  actiondate    datetime            NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '操作时间',
  orderid       int(11)             NOT NULL COMMENT '订单ID',
  productid     int(11)             NOT NULL DEFAULT '0' COMMENT '对应商品ID',
  backbankid    int(11)             NOT NULL COMMENT '退款银行ID',
  faccountid    int(11)             NOT NULL DEFAULT '0' COMMENT '财务科目ID',
  payment       double(12,2)        NOT NULL DEFAULT '0.00' COMMENT '交易金额(订单总额)',
  refund        double(12,2)        NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  staffid       int(11)             NOT NULL COMMENT '操作人',
  comment       varchar(200)        NOT NULL DEFAULT '' COMMENT '备注',
  status        tinyint             NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '财务 - 退款记录';
--/*}}}*/

-- 印刷 - 印刷方式表---/*{{{*/
DROP TABLE IF EXISTS printmethod;
CREATE TABLE printmethod (
  id            int(11)          NOT NULL PRIMARY KEY COMMENT '印刷方式ID',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  name          varchar(50)         NOT NULL COMMENT '印刷方式名称',
  printunitid   int(11)             NOT NULL COMMENT '印刷单位ID',
  type          enum('N','Y')       NOT NULL COMMENT '印刷类型是否制版 N 无，Y 有',
  price         float(12,2)         NOT NULL COMMENT '单价',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除',
  comment       varchar(500)        NOT NULL DEFAULT '' COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '印刷 - 印刷方式表';
--/*}}}*/

-- 印刷 - 印刷单位表---/*{{{*/
DROP TABLE IF EXISTS printunit;
CREATE TABLE printunit (
  id            int(11)          NOT NULL PRIMARY KEY COMMENT '印刷单位ID',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  name          varchar(50)         NOT NULL COMMENT '印刷单位名称',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除',
  comment       varchar(500)        NOT NULL DEFAULT '' COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '印刷 - 印刷单位表';
--/*}}}*/

-- 印刷 - 印刷单图片关联表---/*{{{*/
DROP TABLE IF EXISTS printpic;
CREATE TABLE printpic (
  id            int(11)             NOT NULL PRIMARY KEY COMMENT '印刷单图片ID',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  printid       int(11)             NOT NULL COMMENT '印刷单ID',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除',
  filename      varchar(100)        NOT NULL DEFAULT '' COMMENT '印刷单图片名称'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '印刷 - 印刷单图片关联表';
--/*}}}*/

-- 印刷 - 印刷主表---/*{{{*/
DROP TABLE IF EXISTS printbill;
CREATE TABLE printbill (
  id            int(11)             NOT NULL PRIMARY KEY COMMENT '印刷单ID',
  createtime    datetime            NOT NULL COMMENT '创建时间',
  updatetime    datetime            NOT NULL COMMENT '修改时间',
  printmethodid int(11)             NOT NULL DEFAULT '0' COMMENT '印刷方式ID',
  printunitid   int(11)             NOT NULL DEFAULT '0' COMMENT '印刷单位ID',
  content       varchar(300)        NOT NULL DEFAULT '' COMMENT '印刷内容',
  vnumber       tinyint(3)          NOT NULL DEFAULT '0' COMMENT '制版数',
  pnumber       smallint(5)         NOT NULL DEFAULT '0' COMMENT '产品数量',
  frequency     tinyint(3)          NOT NULL DEFAULT '0' COMMENT '印刷次数',
  position      varchar(20)         NOT NULL DEFAULT '' COMMENT '印刷位置',
  orderid       int(11)             NOT NULL DEFAULT '0' COMMENT '订单编号',
  stylename     varchar(250)        NOT NULL DEFAULT '' COMMENT '款式名称',
  loadaddress   varchar(250)        NOT NULL DEFAULT '' COMMENT '下载地址',
  comstatus     enum('N','Y','R')   NOT NULL DEFAULT 'N' COMMENT '完工状态:N 未完工, Y 已完工, R 返工',
  comdate       datetime            NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '完工时间',
  tpsetstatus   enum('N','Y')       NOT NULL DEFAULT 'N' COMMENT '排版状态:N 未排版, Y 已排版',
  verifystatus  enum('N','Y')       NOT NULL DEFAULT 'N' COMMENT '审核状态:N 未审核, Y 已审核',
  printstatus   enum('N','Y')       NOT NULL DEFAULT 'N' COMMENT '打印状态:N 未打印, Y 已打印',
  printcost     decimal(8,2)        NOT NULL DEFAULT '0.00' COMMENT '印刷成本',
  staffid       int(11)             NOT NULL DEFAULT '0' COMMENT '操作人(员工ID)',
  comment       varchar(500)        NOT NULL DEFAULT '' COMMENT '备注',
  isdelete      enum('Y','N')       NOT NULL DEFAULT 'N' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARACTER SET UTF8 COMMENT '印刷 - 印刷主表';
--/*}}}*/