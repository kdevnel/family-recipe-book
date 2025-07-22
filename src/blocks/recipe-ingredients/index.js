/**
 * Recipe Ingredients Block
 *
 * Displays a list of ingredients for a recipe.
 */

import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import {
    useBlockProps,
    RichText,
    InspectorControls,
} from '@wordpress/block-editor';
import {
    PanelBody,
    TextControl,
    Button,
    Icon,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import {
    plus,
    trash,
    arrowUp,
    arrowDown,
} from '@wordpress/icons';

// Import styles
import './editor.css';
import './style.css';

/**
 * Register the block
 */
registerBlockType(
    'dvnl/recipe-ingredients',
    {
        title: __( 'Recipe Ingredients', 'family-recipe-book' ),
        icon: 'carrot',
        category: 'common',
        attributes: {
            title: {
                type: 'string',
                source: 'meta',
                selector: 'h2',
                meta: '_dvnl_recipe_ingredients_title',
            },
            ingredients: {
                type: 'array',
                source: 'meta',
                meta: '_dvnl_recipe_ingredients_list',
                default: [],
            },
        },
        edit: Edit,
        save: Save,
    }
);

/**
 * Block Edit component
 */
function Edit({ attributes, setAttributes }) {
    const { ingredients, title } = attributes;

    const blockProps = useBlockProps();

    const [newIngredient, setNewIngredient] = useState('');

    const addIngredient = () => {
        if (!newIngredient.trim()) {
            return;
        }

        const updatedIngredients = [...ingredients, newIngredient.trim()];
        setAttributes({ ingredients: updatedIngredients });
        setNewIngredient('');
    };

    const removeIngredient = (index) => {
        const updatedIngredients = [...ingredients];
        updatedIngredients.splice(index, 1);
        setAttributes({ ingredients: updatedIngredients });
    };

    const moveIngredientUp = (index) => {
        if (index === 0) {
            return;
        }

        const updatedIngredients = [...ingredients];
        const item = updatedIngredients[index];
        updatedIngredients.splice(index, 1);
        updatedIngredients.splice(index - 1, 0, item);
        setAttributes({ ingredients: updatedIngredients });
    };

    const moveIngredientDown = (index) => {
        if (index === ingredients.length - 1) {
            return;
        }

        const updatedIngredients = [...ingredients];
        const item = updatedIngredients[index];
        updatedIngredients.splice(index, 1);
        updatedIngredients.splice(index + 1, 0, item);
        setAttributes({ ingredients: updatedIngredients });
    };

    const handleKeyDown = (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            addIngredient();
        }
    };

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Ingredients Settings', 'family-recipe-book')}>
                    <TextControl
                        label={__('Section Title', 'family-recipe-book')}
                        value={title}
                        onChange={(value) => setAttributes({ title: value })}
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <RichText
                    tagName="h2"
                    value={title}
                    onChange={(value) => setAttributes({ title: value })}
                    placeholder={__('Ingredients', 'family-recipe-book')}
                />

                <ul className="dvnl-recipe-ingredients-list">
                    {ingredients.map((ingredient, index) => (
                        <li key={index} className="dvnl-recipe-ingredient-item">
                            <span className="dvnl-recipe-ingredient-text">{ingredient}</span>
                            <div className="dvnl-recipe-ingredient-actions">
                                <Button
                                    icon={arrowUp}
                                    isSmall
                                    onClick={() => moveIngredientUp(index)}
                                    disabled={index === 0}
                                    label={__('Move up', 'family-recipe-book')}
                                />
                                <Button
                                    icon={arrowDown}
                                    isSmall
                                    onClick={() => moveIngredientDown(index)}
                                    disabled={index === ingredients.length - 1}
                                    label={__('Move down', 'family-recipe-book')}
                                />
                                <Button
                                    icon={trash}
                                    isSmall
                                    onClick={() => removeIngredient(index)}
                                    label={__('Remove', 'family-recipe-book')}
                                />
                            </div>
                        </li>
                    ))}
                </ul>

                <div className="dvnl-recipe-ingredient-input">
                    <TextControl
                        value={newIngredient}
                        onChange={setNewIngredient}
                        placeholder={__('Add ingredient...', 'family-recipe-book')}
                        onKeyDown={handleKeyDown}
                    />
                </div>

                <Button
                    className="dvnl-recipe-ingredients-add"
                    icon={plus}
                    onClick={addIngredient}
                    variant="secondary"
                    disabled={!newIngredient.trim()}
                >
                    {__('Add Ingredient', 'family-recipe-book')}
                </Button>
            </div>
        </>
    );
}

/**
 * Block Save component
 */
function Save({ attributes }) {
    const { ingredients, title } = attributes;
    const blockProps = useBlockProps.save();

    return (
        <div {...blockProps}>
            <RichText.Content
                tagName="h2"
                value={title}
            />

            <ul className="dvnl-recipe-ingredients-list">
                { Array.isArray( ingredients ) && ingredients.map( ( ingredient, index ) => (
                    <li key={index} className="dvnl-recipe-ingredient-item">
                        {ingredient}
                    </li>
                ))}
            </ul>
        </div>
    );
}