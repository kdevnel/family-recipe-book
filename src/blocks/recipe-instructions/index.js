/**
 * Recipe Instructions Block
 *
 * Displays step-by-step instructions for a recipe.
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
    TextareaControl,
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
registerBlockType('dvnl/recipe-instructions', {
    edit: Edit,
    save: Save,
});

/**
 * Block Edit component
 */
function Edit({ attributes, setAttributes }) {
    const { steps, title } = attributes;
    const blockProps = useBlockProps();
    const [newStep, setNewStep] = useState('');

    const addStep = () => {
        if (!newStep.trim()) {
            return;
        }

        const updatedSteps = [...steps, newStep.trim()];
        setAttributes({ steps: updatedSteps });
        setNewStep('');
    };

    const removeStep = (index) => {
        const updatedSteps = [...steps];
        updatedSteps.splice(index, 1);
        setAttributes({ steps: updatedSteps });
    };

    const updateStep = (index, value) => {
        const updatedSteps = [...steps];
        updatedSteps[index] = value;
        setAttributes({ steps: updatedSteps });
    };

    const moveStepUp = (index) => {
        if (index === 0) {
            return;
        }

        const updatedSteps = [...steps];
        const item = updatedSteps[index];
        updatedSteps.splice(index, 1);
        updatedSteps.splice(index - 1, 0, item);
        setAttributes({ steps: updatedSteps });
    };

    const moveStepDown = (index) => {
        if (index === steps.length - 1) {
            return;
        }

        const updatedSteps = [...steps];
        const item = updatedSteps[index];
        updatedSteps.splice(index, 1);
        updatedSteps.splice(index + 1, 0, item);
        setAttributes({ steps: updatedSteps });
    };

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Instructions Settings', 'family-recipe-book')}>
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
                    placeholder={__('Instructions', 'family-recipe-book')}
                />

                <ol className="dvnl-recipe-instructions-list">
                    {steps.map((step, index) => (
                        <li key={index} className="dvnl-recipe-step-item">
                            <div className="dvnl-recipe-step-number">{index + 1}</div>
                            <div className="dvnl-recipe-step-text">
                                <TextareaControl
                                    value={step}
                                    onChange={(value) => updateStep(index, value)}
                                    placeholder={__('Enter step instructions...', 'family-recipe-book')}
                                />
                            </div>
                            <div className="dvnl-recipe-step-actions">
                                <Button
                                    icon={arrowUp}
                                    isSmall
                                    onClick={() => moveStepUp(index)}
                                    disabled={index === 0}
                                    label={__('Move up', 'family-recipe-book')}
                                />
                                <Button
                                    icon={arrowDown}
                                    isSmall
                                    onClick={() => moveStepDown(index)}
                                    disabled={index === steps.length - 1}
                                    label={__('Move down', 'family-recipe-book')}
                                />
                                <Button
                                    icon={trash}
                                    isSmall
                                    onClick={() => removeStep(index)}
                                    label={__('Remove', 'family-recipe-book')}
                                />
                            </div>
                        </li>
                    ))}
                </ol>

                <div className="dvnl-recipe-step-textarea">
                    <TextareaControl
                        value={newStep}
                        onChange={setNewStep}
                        placeholder={__('Add a new step...', 'family-recipe-book')}
                    />
                </div>

                <Button
                    className="dvnl-recipe-instructions-add"
                    icon={plus}
                    onClick={addStep}
                    variant="secondary"
                    disabled={!newStep.trim()}
                >
                    {__('Add Step', 'family-recipe-book')}
                </Button>
            </div>
        </>
    );
}

/**
 * Block Save component
 */
function Save({ attributes }) {
    const { steps, title } = attributes;
    const blockProps = useBlockProps.save();

    return (
        <div {...blockProps}>
            <RichText.Content
                tagName="h2"
                value={title}
            />

            <ol className="dvnl-recipe-instructions-list">
                {steps.map((step, index) => (
                    <li key={index} className="dvnl-recipe-step-item" id={`step-${index + 1}`}>
                        {step}
                    </li>
                ))}
            </ol>
        </div>
    );
}