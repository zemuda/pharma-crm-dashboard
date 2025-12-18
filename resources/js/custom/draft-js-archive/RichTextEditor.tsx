import {
    ContentState,
    DraftHandleValue,
    Editor,
    EditorState,
    RichUtils,
    convertFromRaw,
    convertToRaw,
} from 'draft-js';
import 'draft-js/dist/Draft.css';
import React, { useCallback, useRef, useState } from 'react';

interface RichTextEditorProps {
    initialContent?: string;
    onContentChange?: (content: string) => void;
    placeholder?: string;
    readOnly?: boolean;
}

const RichTextEditor: React.FC<RichTextEditorProps> = ({
    initialContent = '',
    onContentChange,
    placeholder = 'Start typing...',
    readOnly = false,
}) => {
    const [editorState, setEditorState] = useState<EditorState>(() => {
        if (initialContent) {
            try {
                const contentState = convertFromRaw(JSON.parse(initialContent));
                return EditorState.createWithContent(contentState);
            } catch {
                const contentState =
                    ContentState.createFromText(initialContent);
                return EditorState.createWithContent(contentState);
            }
        }
        return EditorState.createEmpty();
    });

    const editorRef = useRef<Editor>(null);

    // Focus the editor when container is clicked
    const focusEditor = useCallback(() => {
        if (editorRef.current) {
            editorRef.current.focus();
        }
    }, []);

    // Handle editor state changes
    const handleEditorChange = useCallback(
        (newState: EditorState) => {
            setEditorState(newState);

            if (onContentChange) {
                const content = JSON.stringify(
                    convertToRaw(newState.getCurrentContent()),
                );
                onContentChange(content);
            }
        },
        [onContentChange],
    );

    // Handle key commands for formatting
    const handleKeyCommand = useCallback(
        (command: string, editorState: EditorState): DraftHandleValue => {
            const newState = RichUtils.handleKeyCommand(editorState, command);

            if (newState) {
                handleEditorChange(newState);
                return 'handled';
            }

            return 'not-handled';
        },
        [handleEditorChange],
    );

    // Toggle inline styles
    const toggleInlineStyle = useCallback(
        (inlineStyle: string) => {
            handleEditorChange(
                RichUtils.toggleInlineStyle(editorState, inlineStyle),
            );
        },
        [editorState, handleEditorChange],
    );

    // Toggle block types
    const toggleBlockType = useCallback(
        (blockType: string) => {
            handleEditorChange(
                RichUtils.toggleBlockType(editorState, blockType),
            );
        },
        [editorState, handleEditorChange],
    );

    // Get current inline styles and block type
    const getCurrentInlineStyles = () => {
        const currentStyle = editorState.getCurrentInlineStyle();
        return {
            bold: currentStyle.has('BOLD'),
            italic: currentStyle.has('ITALIC'),
            underline: currentStyle.has('UNDERLINE'),
            code: currentStyle.has('CODE'),
        };
    };

    const getCurrentBlockType = () => {
        const selection = editorState.getSelection();
        const contentState = editorState.getCurrentContent();
        const block = contentState.getBlockForKey(selection.getStartKey());
        return block.getType();
    };

    const currentStyles = getCurrentInlineStyles();
    const currentBlockType = getCurrentBlockType();

    return (
        <div className="rich-text-editor">
            {/* Toolbar */}
            {!readOnly && (
                <div className="editor-toolbar">
                    {/* Block Type Controls */}
                    <div className="toolbar-group">
                        <button
                            type="button"
                            className={`toolbar-btn ${currentBlockType === 'unstyled' ? 'active' : ''}`}
                            onClick={() => toggleBlockType('unstyled')}
                            title="Paragraph"
                        >
                            P
                        </button>
                        <button
                            type="button"
                            className={`toolbar-btn ${currentBlockType === 'header-one' ? 'active' : ''}`}
                            onClick={() => toggleBlockType('header-one')}
                            title="Heading 1"
                        >
                            H1
                        </button>
                        <button
                            type="button"
                            className={`toolbar-btn ${currentBlockType === 'header-two' ? 'active' : ''}`}
                            onClick={() => toggleBlockType('header-two')}
                            title="Heading 2"
                        >
                            H2
                        </button>
                        <button
                            type="button"
                            className={`toolbar-btn ${currentBlockType === 'blockquote' ? 'active' : ''}`}
                            onClick={() => toggleBlockType('blockquote')}
                            title="Blockquote"
                        >
                            ❝
                        </button>
                        <button
                            type="button"
                            className={`toolbar-btn ${currentBlockType === 'code-block' ? 'active' : ''}`}
                            onClick={() => toggleBlockType('code-block')}
                            title="Code Block"
                        >
                            &lt;/&gt;
                        </button>
                    </div>

                    {/* Inline Style Controls */}
                    <div className="toolbar-group">
                        <button
                            type="button"
                            className={`toolbar-btn ${currentStyles.bold ? 'active' : ''}`}
                            onClick={() => toggleInlineStyle('BOLD')}
                            title="Bold"
                        >
                            <strong>B</strong>
                        </button>
                        <button
                            type="button"
                            className={`toolbar-btn ${currentStyles.italic ? 'active' : ''}`}
                            onClick={() => toggleInlineStyle('ITALIC')}
                            title="Italic"
                        >
                            <em>I</em>
                        </button>
                        <button
                            type="button"
                            className={`toolbar-btn ${currentStyles.underline ? 'active' : ''}`}
                            onClick={() => toggleInlineStyle('UNDERLINE')}
                            title="Underline"
                        >
                            U
                        </button>
                        <button
                            type="button"
                            className={`toolbar-btn ${currentStyles.code ? 'active' : ''}`}
                            onClick={() => toggleInlineStyle('CODE')}
                            title="Code"
                        >
                            &lt;&gt;
                        </button>
                    </div>

                    {/* List Controls */}
                    <div className="toolbar-group">
                        <button
                            type="button"
                            className={`toolbar-btn ${currentBlockType === 'unordered-list-item' ? 'active' : ''}`}
                            onClick={() =>
                                toggleBlockType('unordered-list-item')
                            }
                            title="Bullet List"
                        >
                            • List
                        </button>
                        <button
                            type="button"
                            className={`toolbar-btn ${currentBlockType === 'ordered-list-item' ? 'active' : ''}`}
                            onClick={() => toggleBlockType('ordered-list-item')}
                            title="Numbered List"
                        >
                            1. List
                        </button>
                    </div>
                </div>
            )}

            {/* Editor */}
            <div
                className={`editor-container ${readOnly ? 'read-only' : ''}`}
                onClick={focusEditor}
            >
                <Editor
                    ref={editorRef}
                    editorState={editorState}
                    onChange={handleEditorChange}
                    handleKeyCommand={handleKeyCommand}
                    placeholder={placeholder}
                    readOnly={readOnly}
                    spellCheck={true}
                />
            </div>
        </div>
    );
};

export default RichTextEditor;
