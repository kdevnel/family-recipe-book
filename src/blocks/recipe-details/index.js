/**
 * Recipe Details Block
 *
 * A block for displaying recipe details like prep time, cook time, etc.
 */

// Import WordPress dependencies
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import {
    useBlockProps,
    InspectorControls,
} from '@wordpress/block-editor';
import {
    PanelBody,
    TextControl,
    SelectControl,
    RangeControl,
} from '@wordpress/components';
import { Fragment } from '@wordpress/element';

// Import styles
import './editor.css';
import './style.css';

// Register the block
registerBlockType(
    'dvnl/recipe-details',
    {
        title: __( 'Recipe Details', 'family-recipe-book' ),
        icon: 'food',
        category: 'common',
        keywords: [
            __( 'recipe', 'family-recipe-book' ),
            __( 'details', 'family-recipe-book' ),
            __( 'cooking', 'family-recipe-book' ),
        ],
        attributes: {
            prepTime: {
                type: 'string',
                source: 'meta',
                meta: '_dvnl_recipe_prep_time',
            },
            cookTime: {
                type: 'string',
                source: 'meta',
                meta: '_dvnl_recipe_cook_time',
            },
            totalTime: {
                type: 'string',
                source: 'meta',
                meta: '_dvnl_recipe_total_time',
            },
            servings: {
                type: 'string',
                source: 'meta',
                meta: '_dvnl_recipe_servings',
            },
            calories: {
                type: 'string',
                source: 'meta',
                meta: '_dvnl_recipe_calories',
            },
            difficulty: {
                type: 'string',
                source: 'meta',
                meta: '_dvnl_recipe_difficulty',
            },
        },
    // Define the edit interface
        edit: Edit,
    // Define the save interface (empty because we're using a dynamic block)
    save: Save,
});

/**
 * Block Edit component
 */
function Edit({ attributes, setAttributes }) {
    const {
        prepTime,
        cookTime,
        totalTime,
        servings,
        calories,
        difficulty,
    } = attributes;

    const blockProps = useBlockProps();

    const difficultyOptions = [
        { value: 'easy', label: __('Easy', 'family-recipe-book') },
        { value: 'medium', label: __('Medium', 'family-recipe-book') },
        { value: 'hard', label: __('Hard', 'family-recipe-book') },
    ];

    // Update attribute values
    const onChangePrepTime = (value) => {
        setAttributes({ prepTime: value });

        // Auto-calculate total time
        if (cookTime) {
            const prep = parseInt(value, 10) || 0;
            const cook = parseInt(cookTime, 10) || 0;
            setAttributes({ totalTime: (prep + cook).toString() });
        }
    };

    const onChangeCookTime = (value) => {
        setAttributes({ cookTime: value });

        // Auto-calculate total time
        if (prepTime) {
            const prep = parseInt(prepTime, 10) || 0;
            const cook = parseInt(value, 10) || 0;
            setAttributes({ totalTime: (prep + cook).toString() });
        }
    };

    // Get difficulty label
    const getDifficultyLabel = () => {
        if (difficulty === 'easy') {
            return __('Easy', 'family-recipe-book');
        } else if (difficulty === 'medium') {
            return __('Medium', 'family-recipe-book');
        } else if (difficulty === 'hard') {
            return __('Hard', 'family-recipe-book');
        }
        return '';
    };

    return (
        <Fragment>
            <InspectorControls>
                <PanelBody title={__('Recipe Details', 'family-recipe-book')}>
                    <TextControl
                        label={__('Preparation Time (minutes)', 'family-recipe-book')}
                        value={prepTime}
                        onChange={onChangePrepTime}
                        type="number"
                        min={0}
                    />
                    <TextControl
                        label={__('Cooking Time (minutes)', 'family-recipe-book')}
                        value={cookTime}
                        onChange={onChangeCookTime}
                        type="number"
                        min={0}
                    />
                    <TextControl
                        label={__('Total Time (minutes)', 'family-recipe-book')}
                        value={totalTime}
                        onChange={(value) => setAttributes({ totalTime: value })}
                        type="number"
                        min={0}
                    />
                    <RangeControl
                        label={__('Servings', 'family-recipe-book')}
                        value={servings}
                        onChange={(value) => setAttributes({ servings: value })}
                        min={1}
                        max={20}
                    />
                    <TextControl
                        label={__('Calories (per serving)', 'family-recipe-book')}
                        value={calories}
                        onChange={(value) => setAttributes({ calories: value })}
                        type="number"
                        min={0}
                    />
                    <SelectControl
                        label={__('Difficulty', 'family-recipe-book')}
                        value={difficulty}
                        options={difficultyOptions}
                        onChange={(value) => setAttributes({ difficulty: value })}
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <h2>{__('Recipe Details', 'family-recipe-book')}</h2>
                <div className="dvnl-recipe-details-grid">
                    <div className="dvnl-recipe-detail">
                        <span className="dvnl-recipe-detail-label">{__('Prep Time', 'family-recipe-book')}</span>
                        <span className="dvnl-recipe-detail-value">{prepTime} {__('min', 'family-recipe-book')}</span>
                    </div>
                    <div className="dvnl-recipe-detail">
                        <span className="dvnl-recipe-detail-label">{__('Cook Time', 'family-recipe-book')}</span>
                        <span className="dvnl-recipe-detail-value">{cookTime} {__('min', 'family-recipe-book')}</span>
                    </div>
                    <div className="dvnl-recipe-detail">
                        <span className="dvnl-recipe-detail-label">{__('Total Time', 'family-recipe-book')}</span>
                        <span className="dvnl-recipe-detail-value">{totalTime} {__('min', 'family-recipe-book')}</span>
                    </div>
                    <div className="dvnl-recipe-detail">
                        <span className="dvnl-recipe-detail-label">{__('Servings', 'family-recipe-book')}</span>
                        <span className="dvnl-recipe-detail-value">{servings}</span>
                    </div>
                    <div className="dvnl-recipe-detail">
                        <span className="dvnl-recipe-detail-label">{__('Calories', 'family-recipe-book')}</span>
                        <span className="dvnl-recipe-detail-value">{calories} {__('kcal', 'family-recipe-book')}</span>
                    </div>
                    <div className="dvnl-recipe-detail">
                        <span className="dvnl-recipe-detail-label">{__('Difficulty', 'family-recipe-book')}</span>
                        <span className="dvnl-recipe-detail-value">
                            {difficulty === 'easy' && __('Easy', 'family-recipe-book')}
                            {difficulty === 'medium' && __('Medium', 'family-recipe-book')}
                            {difficulty === 'hard' && __('Hard', 'family-recipe-book')}
                        </span>
                    </div>
                </div>
            </div>
        </Fragment>
    );
}

/**
 * Block Save component
 */
function Save({ attributes }) {
    const {
        prepTime,
        cookTime,
        totalTime,
        servings,
        calories,
        difficulty,
    } = attributes;

    const blockProps = useBlockProps.save();

    return (
        <div {...blockProps}>
            <h2>{__('Recipe Details', 'family-recipe-book')}</h2>
            <div className="dvnl-recipe-details-grid">
                <div className="dvnl-recipe-detail">
                    <span className="dvnl-recipe-detail-label">{__('Prep Time', 'family-recipe-book')}</span>
                    <span className="dvnl-recipe-detail-value">{prepTime} {__('min', 'family-recipe-book')}</span>
                </div>
                <div className="dvnl-recipe-detail">
                    <span className="dvnl-recipe-detail-label">{__('Cook Time', 'family-recipe-book')}</span>
                    <span className="dvnl-recipe-detail-value">{cookTime} {__('min', 'family-recipe-book')}</span>
                </div>
                <div className="dvnl-recipe-detail">
                    <span className="dvnl-recipe-detail-label">{__('Total Time', 'family-recipe-book')}</span>
                    <span className="dvnl-recipe-detail-value">{totalTime} {__('min', 'family-recipe-book')}</span>
                </div>
                <div className="dvnl-recipe-detail">
                    <span className="dvnl-recipe-detail-label">{__('Servings', 'family-recipe-book')}</span>
                    <span className="dvnl-recipe-detail-value">{servings}</span>
                </div>
                <div className="dvnl-recipe-detail">
                    <span className="dvnl-recipe-detail-label">{__('Calories', 'family-recipe-book')}</span>
                    <span className="dvnl-recipe-detail-value">{calories} {__('kcal', 'family-recipe-book')}</span>
                </div>
                <div className="dvnl-recipe-detail">
                    <span className="dvnl-recipe-detail-label">{__('Difficulty', 'family-recipe-book')}</span>
                    <span className="dvnl-recipe-detail-value">
                        {difficulty === 'easy' && __('Easy', 'family-recipe-book')}
                        {difficulty === 'medium' && __('Medium', 'family-recipe-book')}
                        {difficulty === 'hard' && __('Hard', 'family-recipe-book')}
                    </span>
                </div>
            </div>
        </div>
    );
}