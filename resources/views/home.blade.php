<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoleSenseAI - Resume Classifier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h4 mb-0">RoleSenseAI - SAP Resume Classifier</h1>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if(session('file_path'))
                            <div class="alert alert-success">Resume uploaded successfully! Click "Analyze Resume" to get AI insights.</div>
                        @endif

                        <form action="{{ route('upload.web') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label for="resume" class="form-label">Upload Resume</label>
                                <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx,.txt" required>
                                <div class="form-text">Supported formats: PDF, DOC, DOCX, TXT (Max 2MB)</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload Resume</button>
                        </form>

                        @if(session('file_path'))
                            <form action="{{ route('match.web') }}" method="POST" class="mb-4">
                                @csrf
                                <input type="hidden" name="file_path" value="{{ session('file_path') }}">
                                <button type="submit" class="btn btn-success">Analyze Resume with AI</button>
                            </form>
                        @endif

                        @if(session('result'))
                            <div class="mt-4">
                                <h3>Analysis Results</h3>
                                <div class="row">
                                    <div class="col-md-8">
                                        <p><strong>Summary:</strong> {{ session('result')['summary'] }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="badge bg-info fs-6">Suggested Role: {{ session('result')['role'] }}</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <h5>Skill Gaps:</h5>
                                    <ul>
                                        @foreach(session('result')['skill_gaps'] as $gap)
                                            <li>{{ $gap }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="mt-3">
                                    <h5>Learning Tips:</h5>
                                    <ul>
                                        @foreach(session('result')['learning_tips'] as $tip)
                                            <li>{{ $tip }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
