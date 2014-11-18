<?php namespace Anomaly\Streams\Addon\FieldType\Multiple;

use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Anomaly\Streams\Platform\Model\EloquentModel;

/**
 * Class MultipleFieldType
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Addon\FieldType\Multiple
 */
class MultipleFieldType extends FieldType
{

    /**
     * The input class.
     *
     * @var null
     */
    protected $class = null;

    /**
     * The input view.
     *
     * @var string
     */
    protected $inputView = 'field_type.multiple::input';

    /**
     * Get the relation.
     *
     * @return array
     */
    public function getRelation()
    {
        return $this->hasOne($this->getConfig('related'));
    }

    /**
     * Get view data for the input.
     *
     * @return array
     */
    public function getInputData()
    {
        $data = parent::getInputData();

        $data['options'] = $this->getOptions();

        return $data;
    }

    /**
     * Get the options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = [];

        foreach ($this->getModelOptions() as $option) {

            $option['selected'] = in_array($option['value'], $this->getValue());

            $options[] = $option;
        }

        return $options;
    }

    /**
     * Get options from the model.
     *
     * @return array
     */
    protected function getModelOptions()
    {
        $model = $this->getRelatedModel();

        if (!$model instanceof EloquentModel) {

            return [];
        }

        $options = [];

        foreach ($model->all() as $entry) {

            $value = $entry->getKey();

            if ($title = $this->getConfig('title')) {

                $title = $entry->{$title};
            }

            if (!$title) {

                $title = $entry->getTitle();
            }

            $entry = $entry->toArray();

            $options[] = compact('value', 'title', 'entry');
        }

        return $options;
    }

    /**
     * @return null
     */
    protected function getRelatedModel()
    {
        $model = $this->getConfig('related');

        if (!$model) {

            return null;
        }

        return app()->make($model);
    }

    /**
     * Get the value.
     *
     * @return array
     */
    public function getValue()
    {
        return (array)parent::getValue();
    }
}
