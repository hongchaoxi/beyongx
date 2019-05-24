<?php
namespace app\common\model;

use app\common\exception\ModelException;
use think\facade\Log;

class ArticleModel extends BaseModel
{
    protected $name = CMS_PREFIX . 'article';

    const STATUS_DELETED = -1;//删除
    const STATUS_DRAFT = 0; //草稿
    const STATUS_PUBLISHING = 1; //申请发布
    const STATUS_FIRST_AUDIT_REJECT = 2; //初审拒绝
    const STATUS_FIRST_AUDITED = 3; //初审通过
    const STATUS_SECOND_AUDIT_REJECT = 4; //终审拒绝
    const STATUS_PUBLISHED = 5; //已发布

    protected $pk = 'id';

    protected $auto = ['last_update_time'];
    protected $insert = ['status','create_time','user_id'];
    protected $update = ['last_update_time'];

    //静态初始化时，依赖注入事件
    public static function init()
    {
        //计算文章相似度，article_a_id > article_b_id
        self::event('after_insert', function($article) {
//            $lcs = new \app\common\library\LCS();
//
//            $ArticleModel = new ArticleModel();
//            $list = $ArticleModel->where([['id', '<', $article->id]])->field('id,title')->select();
//            foreach ($list as $temp) {
//                $titleSimilar = $lcs->getSimilar($article->title, $temp->title);
//                $contentSimilar = $titleSimilar;
//                $articleData = [
//                    'article_a_id' => $article->id,
//                    'article_b_id' => $temp->id,
//                    'title_similar' => $titleSimilar,
//                    'content_similar' => $contentSimilar,
//                    'last_update_time' => date_time(),
//                    'create_time' => date_time(),
//                ];
//                Db::name(CMS_PREFIX . 'article_data')->insert($articleData);
//            }

            $id = $article->id;

            //指定任务的处理类，若指定至方法时，@methodName
            $jobHandlerClass  = 'app\admin\job\Article@afterInsert';
            //任务的业务数据 . 不能为 resource 类型，其他类型最终将转化为json形式的字符串; jobData 为对象时，存储其public属性的键值对
            $jobData = ['id' => $id];
            //任务归属的队列名称，如果为新队列，会自动创建
            $jobQueue = config('queue.default');

            $isPushed = \think\Queue::push($jobHandlerClass, $jobData, $jobQueue);
            // database 驱动时，返回值为 1|false; redis 驱动时，返回值为 随机字符串|false
            if ($isPushed !== false) {
                Log::info('文章相似度LCS更新Job入列成功...');
            } else {
                Log::info('文章相似度LCS更新入列失败！');
            }
        });
        self::event('after_update', function($article) {
//            $lcs = new \app\common\library\LCS();
//
//            $articleBIds = Db::name(CMS_PREFIX . 'article_data')->where('article_a_id', $article->id)->column('id,create_time', 'article_b_id');
//            $articleAIds = Db::name(CMS_PREFIX . 'article_data')->where('article_b_id', $article->id)->column('id,create_time', 'article_a_id');
//            $ids = array_merge(array_keys($articleBIds), array_keys($articleAIds));
//            //Log::debug($articleBIds);
//            //Log::debug($articleAIds);
//            //Log::debug($ids);
//
//            $ArticleModel = new ArticleModel();
//            $list = $ArticleModel->where([['id', 'in', $ids]])->field('id,title')->select();
//            foreach ($list as $temp) {
//                $titleSimilar = $lcs->getSimilar($article->title, $temp->title);
//                $contentSimilar = $titleSimilar;
//                $articleDataId = null;
//                if (array_key_exists($temp->id, $articleBIds)) {
//                    $articleDataId = $articleBIds[$temp->id]['id'];
//                } else {
//                    $articleDataId = $articleAIds[$temp->id]['id'];
//                }
//                $articleData = [
//                    'title_similar' => $titleSimilar,
//                    'content_similar' => $contentSimilar,
//                    'last_update_time' => date_time(),
//                ];
//                Db::name(CMS_PREFIX . 'article_data')->where('id', $articleDataId)->update($articleData);
//            }

            $id = $article->id;

            //指定任务的处理类，若指定至方法时，@methodName
            $jobHandlerClass  = 'app\admin\job\Article@afterUpdate';
            //任务的业务数据 . 不能为 resource 类型，其他类型最终将转化为json形式的字符串; jobData 为对象时，存储其public属性的键值对
            $jobData = ['id' => $id];
            //任务归属的队列名称，如果为新队列，会自动创建
            $jobQueue = config('queue.default');

            $isPushed = \think\Queue::push($jobHandlerClass, $jobData, $jobQueue);
            // database 驱动时，返回值为 1|false; redis 驱动时，返回值为 随机字符串|false
            if ($isPushed !== false) {
                Log::info('文章相似度LCS更新Job入列成功...');
            } else {
                Log::info('文章相似度LCS更新入列失败！');
            }
        });
    }

    //属性：status_text
    public function getStatusTextAttr($value, $data)
    {
        $status = [
            -1 => '删除',
            0 => '草稿',
            1 => '申请发布',
            2 => '初审拒绝',
            3 => '初审通过',
            4 => '终审拒绝',
            5 => '已发布',
        ];
        return isset($status[$data['status']])?$status[$data['status']] : '未知';
    }

    //属性: timing 定时
    public function getTimingAttr($value, $data)
    {
        $id = $data['id'];
        $ArticleMetaModel = new ArticleMetaModel();
        $articleMeta = $ArticleMetaModel->getMeta($id, ArticleMetaModel::KEY_TIMING_POST);
        return $articleMeta ? $articleMeta['meta_value'] : '0';
    }

    //关联表：缩略图
    public function thumbImage()
    {
        return $this->hasOne('ImageModel', 'image_id', 'thumb_image_id');
    }

    //关联表：文章分类
    protected function categorys()
    {
        return $this->belongsToMany('CategoryModel', config('database.prefix'). CMS_PREFIX . 'category_article', 'category_id', 'article_id');
    }
    //关联表：中间表，用于获取中间表数据，或查询has/hasWhere
    protected function categoryArticle()
    {
        return $this->hasMany('CategoryArticleModel','article_id','id');
    }

    //关联表：用户
    protected function user()
    {
        return $this->belongsTo('UserModel', 'user_id');
    }

    //关联表：用户
    protected function comments()
    {
        return $this->hasMany('CommentModel', 'article_id');
    }

    //新增文章
    public function add($data = [])
    {
        $data = $data?:input('post.');

        $validator = new \app\common\validate\Article();
        $check = $validator->scene('add')->check($data);
        if ($check !== true) {
            throw new ModelException(0, $validator->getError());
        }

        if ($data['status'] == ArticleModel::STATUS_PUBLISHING || $data['status'] == ArticleModel::STATUS_PUBLISHED) {
            $data['post_time'] = date_time();
        }

        $res = $this->allowField(true)->isUpdate(false)->save($data);

        if (!$res) {
            return false;
        }

        //只新增中间表数据
        $this->categorys()->saveAll($data['category_ids']);

        return true;
    }

    //修改文章
    public function edit($data = [])
    {
        $data = $data?:input('post.');
        $art = self::get(['id'=>$data['id']]);
        if (empty($art)) {
            throw new ModelException(0, '文章不存在');
        }

        if ($art->status == ArticleModel::STATUS_DRAFT && $data['status'] == ArticleModel::STATUS_PUBLISHED) {
            //审核开关关闭时
            if (get_config('article_audit_switch') === 'true' ) {
                $data['status'] = ArticleModel::STATUS_PUBLISHING;
            }
            if (empty($art->post_time)) {
                $data['post_time'] = date_time();//设置发布时间
            }
        }

        $validate = validate('Article');
        $check = $validate->scene('edit')->check($data);
        if ($check !== true) {
            throw new ModelException(0, $validate->getError());
            return false;
        }

        $res = $this->allowField(true)->isUpdate(true)->save($data);

        // 删除中间表数据
        if (!empty($data['category_ids'])) {
            $art->categorys()->detach();
            $art->categorys()->saveAll($data['category_ids']);
        }

        return true;
    }


    //文章列表获取
//    public static function getList($cateId,$limit = 7, $map = '=')
//    {
//        $cacheMark = 'article_cate_'.$cateId;
//        if (session('uid')) {
//            $tag = 'login_';
//        } else {
//            $tag = 'logout_';
//        }
//
//        if (!Cache::has($tag.$cacheMark)) {
//            $childCate = CategoryModel::getChild($cateId);
//            $articleModel = ArticleModel::has('categoryArticle',['category_id'=>['in',$childCate['ids']]]);
//            if ($tag == 'login_') {
//                $where['post_type'] = ['egt',0];
//            } else {
//                $where['post_type'] = 0;
//            }
//            $list = $articleModel->where('status',['=', ArticleModel::STATUS_PUBLISHED_OLD], ['=', ArticleModel::STATUS_PUBLISHED], 'or')
//                ->where($where)->field('id,category_id,title,author,post_time,thumb_image_id,description')->order('is_top desc,post_time desc')->limit($limit)->select();
//
//            // dump($list);die;
//            foreach ($list as $k => $v) {
//                $list[$k] = $v->toArray();
//                if ($v['thumb_image_id'] > 0) {
//                    $list[$k]['image_url'] = url_add_domain($v->image->image_url);
//                    $list[$k]['thumb_image_url'] = url_add_domain($v->image->thumbnail_image_url);
//                } else {
//                    $list[$k]['image_url'] = '';
//                    $list[$k]['thumb_image_url'] = '';
//                }
//            }
//            Cache::set($tag.$cacheMark,$list);
//        }
//        $artList = Cache::get($tag.$cacheMark);
//
//        return $artList;
//    }

    //删除文章缓存
    public static function clearArticleCache($cateIds)
    {
        foreach ($cateIds as $cateId) {
            if ($cateId instanceof \think\Model) {
                $cateId = $cateId['id'];
            }
            $cate = CategoryModel::getParent($cateId);
            foreach ($cate['ids'] as $k => $v) {
                if (Cache::has('login_article_cate_'.$v)) {
                    Cache::rm('login_article_cate_'.$v);
                }
                if (Cache::has('logout_article_cate_'.$v)) {
                    Cache::rm('logout_article_cate_'.$v);
                }
            }
        }
    }
}

?>