<?php

namespace Marshmallow\IpAccess\Nova;

use App\Nova\Resource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Boolean;
use Marshmallow\IpAccess\Models\IpAccess as IpAccessModel;
use Marshmallow\HelperFunctions\Facades\Ip;

class IpAccess extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Marshmallow\IpAccess\Models\IpAccess';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string|null
     */
    public function subtitle()
    {
        return $this->ip_address;
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Ip Access');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Ip Access');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'ip_address_v4', 'ip_address_v6', 'ip_address'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param Laravel\Nova\Http\Requests\NovaRequest $request Request
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make(__('Type'), 'type')
                ->options([
                    IpAccessModel::SINGLE => __('Single IP address'),
                    IpAccessModel::RANGE => __('IP range'),
                ])
                ->rules(['required'])
                ->required(),

            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules(['required'])
                ->required(),

            Boolean::make(__('Backoffice access'), 'backoffice_access'),

            Text::make(__('IP Address'), 'ip_address')
                ->withMeta($this->ip_address ? [] : [
                    'value' => $request->ip(),
                ])
                ->displayUsing(function ($value, $resource) use ($request) {
                    $return = $value;
                    if ($resource->isCurrentIp($value)) {
                        $return .= '<span class="ml-2 bg-success text-white p-1 pl-2 pr-2 rounded-sm inline-block text-sm">';
                        $return .= __('This is you');
                        $return .= '</span>';
                    }
                    return $return;
                })
                ->asHtml()
                ->rules(['required', 'unique:ip_accesses,ip_address,{{resourceId}}'])
                ->required(),

            DateTime::make(__('Has access from'), 'from'),
            DateTime::make(__('Has access till'), 'till'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
