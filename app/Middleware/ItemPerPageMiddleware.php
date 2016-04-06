<?php
namespace App\Middleware;

use App\Helpers\SessionManager as Session;
use App\Source\Factory\ModelsFactory;
use App\Helpers\RequestParams;

class ItemPerPageMiddleware
{
    protected $variableName = 'count_page';
    protected $groupName = 'items.perpage.';

    public function __invoke($request, $response, $next)
    {

        $allParams = new RequestParams($request);

        if (Session::has('auth') &&
            Session::get('auth') &&
            $allParams->all($this->variableName)
        ) {
            $this->groupName = $this->groupName . basename($allParams->getUri()->getPath());

            $u_id = Session::get('user')['id'];
        	$model = ModelsFactory::getModel('UserViewsSettings');
            $result = $model->where('user_id', $u_id)->where('group', $this->groupName)->where('code', $this->variableName)->first();

            if (!$result) {
                $result = ModelsFactory::getModel('UserViewsSettings', ['user_id' => $u_id, 'group' => $this->groupName, 'code' => $this->variableName]);
                $result->user_id = $u_id;
            }

            $result->value = $allParams->all($this->variableName);
            $result->save();

            Session::push('admin_panel.count_page', $allParams->all($this->variableName));
        }

        return $next($request, $response);
    }
}
