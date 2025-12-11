import sys
import json
import os

# New LangChain imports
from langchain_openai import ChatOpenAI
from langchain_core.prompts import ChatPromptTemplate

def extract_text(file_path):
    if file_path.endswith('.txt'):
        try:
            with open(file_path, 'r') as f:
                return f.read()
        except FileNotFoundError:
            return "File not found: " + file_path
    else:
        return "Sample resume text: Experienced in SAP MM module, etc."

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

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python ai_agent.py <file_path>")
        sys.exit(1)
    main(sys.argv[1])
