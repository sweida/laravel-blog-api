### 还未完成的功能：
- [ ] 访问次数，用visit插件
- [ ] 图片上传到又拍云，https://github.com/JellyBool/flysystem-upyun
- [ ] 用户登录2小时后会过期
- [ ] 第一次访问网站时间过长
- [ ] travis CI自动化部署
- [ ] 网站图标
- [x] 上一篇和下一篇文章
- [ ] 给大的语言类型各自页面




# 后端API文档 v1.0.0
## 常规API调用原则
* 所有API都以domain.com/api/...开头
* API分为两部分，如 `domain.com/api/part_1/part_2`
  * `part_1`为model名称，如 `user` 或 `question`
  * `part_2`为行为的名称，如 `reset_password`
- CRUD
  - 每个model中都会有增删改查四个方法，分别对应为 `add`、`remove`、`change`、`read`

## Model
### Question
#### 字段解释
- `id`
- `title` ：标题
- `desc` ：描述

#### `add`
- 权限：登录
- 传参：
  - 必填：`title` (标题)
  - 可选：`desc` (描述)

#### `change`
- 权限：已登录且为问题的所有者
- 传参：
  - 必填：`id` (问题id)
  - 可选：`title`, `desc`