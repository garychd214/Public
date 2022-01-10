using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;

public class TrackCheckpoints : MonoBehaviour
{
    private List<CheckpointSingle> checkpointSingleList; //keep track of order
    private int nextCheckpoint;

    private bool NotFinished = true;
    
    public Text timer;
    public Text timeend;
    private float Time2;
    
    public Text Wrong;


    private void Update()
    {
        if (NotFinished == true)
        {
            Time2 += Time.deltaTime;
            timer.text = Time2.ToString("f2");
            timeend.text = Time2.ToString("f2");

            if (NotFinished == false)
            {
                Time2 = 0;
            }
        }
    }
   

    private void Awake()
    {
        Wrong.gameObject.SetActive(false);
        timeend.gameObject.SetActive(false);

        Transform CheckpointsTransform = transform.Find("Checkpoints");

        checkpointSingleList = new List<CheckpointSingle>();
        foreach (Transform checkpointSingleTransform in CheckpointsTransform)
        {
            CheckpointSingle checkpointSingle = checkpointSingleTransform.GetComponent<CheckpointSingle>();
            checkpointSingle.SetTrackCheckpoint(this);
            checkpointSingleList.Add(checkpointSingle);
        }

        nextCheckpoint = 0;
    }

    public void PlayerThroughCheckpoint(CheckpointSingle checkpointSingle)
    {
        if (checkpointSingleList.IndexOf(checkpointSingle) == nextCheckpoint)
        {
            nextCheckpoint++;
            Debug.Log("Correct checkpoint");
            Wrong.gameObject.SetActive(false);
            if (checkpointSingleList.IndexOf(checkpointSingle) == 5)
            {
                NotFinished = false;
                
                timeend.gameObject.SetActive(true);
                StartCoroutine(playaginscene(2));
            }
        }
        
        else
        {
            Wrong.gameObject.SetActive(true);
            Debug.Log("WRONG WAY");
        }

    }
    

    IEnumerator playaginscene(float delayTime)
    {
        yield return new WaitForSeconds(4);
       
        SceneManager.LoadScene("playagain");
        
    }

    

}
