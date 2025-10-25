import React, { useCallback, useState } from 'react';
import './editor.css';
import RichTextEditor from './RichTextEditor';

const DraftEditor: React.FC = () => {
    const [editorContent, setEditorContent] = useState<string>('');
    const [savedContent, setSavedContent] = useState<string>('');

    const handleContentChange = useCallback((content: string) => {
        setEditorContent(content);
    }, []);

    const handleSave = () => {
        setSavedContent(editorContent);
        alert('Content saved! Check the preview below.');
    };

    const handleClear = () => {
        setEditorContent('');
        setSavedContent('');
    };

    return (
        <div className="app">
            <header className="app-header">
                <h1>Draft.js Rich Text Editor</h1>
                <p>React TypeScript Example</p>
            </header>

            <main className="app-main">
                <div className="editor-section">
                    <div className="section-header">
                        <h2>Editor</h2>
                        <div className="actions">
                            <button
                                onClick={handleSave}
                                className="btn btn-primary"
                            >
                                Save Content
                            </button>
                            <button
                                onClick={handleClear}
                                className="btn btn-secondary"
                            >
                                Clear
                            </button>
                        </div>
                    </div>

                    <RichTextEditor
                        onContentChange={handleContentChange}
                        placeholder="Start writing your content here..."
                    />
                </div>

                <div className="preview-section">
                    <div className="section-header">
                        <h2>Preview</h2>
                    </div>

                    <div className="preview-container">
                        {savedContent ? (
                            <RichTextEditor
                                initialContent={savedContent}
                                readOnly={true}
                            />
                        ) : (
                            <div className="no-content">
                                <p>
                                    No content saved yet. Write something and
                                    click "Save Content" to see the preview.
                                </p>
                            </div>
                        )}
                    </div>
                </div>

                <div className="raw-content-section">
                    <div className="section-header">
                        <h2>Raw Content (JSON)</h2>
                    </div>

                    <pre className="raw-content">
                        {editorContent
                            ? JSON.stringify(JSON.parse(editorContent), null, 2)
                            : '{}'}
                    </pre>
                </div>
            </main>
        </div>
    );
};

export default DraftEditor;
