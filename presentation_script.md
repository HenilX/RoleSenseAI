# RoleSenseAI Presentation Script

## Slide 1: Introduction to RoleSenseAI
Good [morning/afternoon], everyone. Today, I'm excited to present RoleSenseAI, an innovative Laravel-based API application that leverages artificial intelligence to revolutionize resume analysis for SAP roles. This project automates the classification of resumes into specific SAP modules like MM (Materials Management), FICO (Financial Accounting and Controlling), PM (Plant Maintenance), SD (Sales and Distribution), and Basis, while providing insightful summaries, skill gap analyses, and personalized learning tips.

## Slide 2: Project Overview and Features
RoleSenseAI is designed to streamline HR processes in SAP-focused organizations. Key features include:
- **Resume Upload**: Supports multiple file formats (PDF, DOC, DOCX, TXT) with a 2MB size limit.
- **AI-Powered Analysis**: Utilizes LangChain and OpenAI's GPT-3.5-turbo for intelligent processing.
- **Structured Output**: Returns JSON responses with candidate summaries, role assignments, skill gaps, learning tips, and detailed reasoning steps.
- **Logging**: Comprehensive logging of AI reasoning for transparency and debugging.

The project assumes basic text extraction for simplicity and uses Ollama with Llama2 for AI processing, though it can be extended to more advanced models.

## Slide 3: Architecture and Tech Stack
RoleSenseAI follows a microservices-inspired architecture:
- **Backend**: Laravel (PHP) for API management and file handling.
- **AI Engine**: Python script using LangChain for prompt engineering and OpenAI integration.
- **Deployment**: Containerized with Docker and orchestrated via Kubernetes for scalability.
- **Frontend**: Basic web interface using Blade templates for demonstration.

Here's a high-level diagram [show diagram]:
- User uploads resume → Laravel stores file → Calls Python AI agent → Returns JSON analysis.

## Slide 4: Key Code Explanation - Laravel Controller (ResumeController.php)
Let's dive into the core logic. The ResumeController handles two main endpoints: upload and match.

**Upload Method**:
```php
public function upload(Request $request)
{
    $request->validate([
        'resume' => 'required|file|mimes:pdf,doc,docx,txt|max:2048',
    ]);

    $file = $request->file('resume');
    $path = $file->store('resumes');
    $fullPath = storage_path('app/' . $path);

    return response()->json([
        'message' => 'Resume uploaded successfully',
        'file_path' => $fullPath,
    ]);
}
```
This method validates the uploaded file, stores it securely in Laravel's storage, and returns the file path for subsequent processing. It ensures only supported formats are accepted and enforces size limits for security.

**Match Method**:
```php
public function match(Request $request)
{
    $request->validate([
        'file_path' => 'required|string',
    ]);

    $filePath = $request->input('file_path');

    // Call Python script for AI processing
    $output = shell_exec("python " . base_path('scripts/ai_agent.py') . " " . escapeshellarg($filePath));

    $result = json_decode($output, true);

    if (!$result) {
        return response()->json(['error' => 'AI processing failed'], 500);
    }

    // Log reasoning steps
    Log::info('AI Agent Reasoning', $result['reasoning']);

    return response()->json($result);
}
```
Here, the controller invokes the Python AI agent via shell execution, decodes the JSON output, logs the reasoning for auditability, and returns the analysis. This integration bridges PHP and Python seamlessly.

## Slide 5: Key Code Explanation - AI Agent (ai_agent.py)
The heart of the AI processing lies in this Python script, which uses LangChain for structured prompting.

**Text Extraction**:
```python
def extract_text(file_path):
    if file_path.endswith('.txt'):
        try:
            with open(file_path, 'r') as f:
                return f.read()
        except FileNotFoundError:
            return "File not found: " + file_path
    else:
        return "Sample resume text: Experienced in SAP MM module, etc."
```
For demonstration, it handles TXT files directly and provides sample text for others. In production, this could integrate libraries like PyPDF2 or docx for full extraction.

**Prompt Template and Chain**:
```python
PROMPT = ChatPromptTemplate.from_template("""
You are an AI agent that analyzes a resume and outputs strict JSON.

Extract:
- summary: 2-3 sentences
- role: one of ["MM","FICO","PM","SD","Basis"]
- skill_gaps: 3 items
- learning_tips: 3 items
- reasoning: array of steps explaining the analysis

Return ONLY valid JSON.

Resume:
\"\"\"{resume_text}\"\"\"
""")

def main(file_path):
    resume_text = extract_text(file_path)

    llm = ChatOpenAI(model="gpt-3.5-turbo", temperature=0)
    chain = PROMPT | llm

    response = chain.invoke({"resume_text": resume_text})
    raw = response.content

    try:
        parsed = json.loads(raw)
    except:
        parsed = {"error": "Invalid JSON from LLM", "raw": raw}

    print(json.dumps(parsed))
```
The prompt template guides the AI to produce consistent, structured JSON. LangChain chains the prompt with the LLM, ensuring reliable output. Error handling catches JSON parsing issues.

## Slide 6: API Endpoints and Routing
RoleSenseAI exposes a simple REST API defined in routes/api.php:

```php
Route::post('/resume', [ResumeController::class, 'upload']);
Route::post('/match', [ResumeController::class, 'match']);
```

- **POST /api/resume**: Uploads the resume and returns the file path.
- **POST /api/match**: Analyzes the resume using the provided file path and returns the AI-generated insights.

Example API flow:
1. Upload resume → Get file_path.
2. Send file_path to /match → Receive JSON with summary, role, gaps, tips, and reasoning.

## Slide 7: Deployment and Setup
For deployment, RoleSenseAI supports multiple environments:

**Local Development**:
- Run `php artisan serve` for Laravel.
- Ensure Python dependencies (LangChain, OpenAI) are installed.
- Set OPENAI_API_KEY in .env.

**Docker**:
- Use docker-compose.yml to build and run containers.
- Dockerfile packages the Laravel app with PHP and necessary extensions.

**Kubernetes**:
- k8s.yaml defines deployments, services, and pods for scalable orchestration.

Prerequisites include PHP 8.2+, Composer, Python 3.x, Docker, and Kubernetes.

## Slide 8: Future Improvements and Conclusion
Potential enhancements include advanced text extraction, authentication, caching, and a full frontend UI. RoleSenseAI demonstrates the power of AI in HR automation, providing actionable insights for SAP role assignments.

Thank you for your attention. I'm happy to take any questions!

[End of Presentation]
